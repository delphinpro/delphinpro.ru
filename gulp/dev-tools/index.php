<?
/*
 * @author delphinpro <delphinpro@gmail.com>
 * @copyright copyright © 2017 delphinpro
 * @license licensed under the MIT license
 */

#
#
# Parsedown
# http://parsedown.org
#
# (c) Emanuil Rusev
# http://erusev.com
#
# For the full license information, view the LICENSE file that was distributed
# with this source code.
#
#

class Parsedown
{
    # ~

    const version = '1.6.0';

    # ~
    private static $instances = array();

    #
    # Setters
    #
    protected $breaksEnabled;
    protected $markupEscaped;
    protected $urlsLinked = true;
    protected $BlockTypes = array(
        '#' => array('Header'),
        '*' => array('Rule', 'List'),
        '+' => array('List'),
        '-' => array('SetextHeader', 'Table', 'Rule', 'List'),
        '0' => array('List'),
        '1' => array('List'),
        '2' => array('List'),
        '3' => array('List'),
        '4' => array('List'),
        '5' => array('List'),
        '6' => array('List'),
        '7' => array('List'),
        '8' => array('List'),
        '9' => array('List'),
        ':' => array('Table'),
        '<' => array('Comment', 'Markup'),
        '=' => array('SetextHeader'),
        '>' => array('Quote'),
        '[' => array('Reference'),
        '_' => array('Rule'),
        '`' => array('FencedCode'),
        '|' => array('Table'),
        '~' => array('FencedCode'),
    );
    protected $unmarkedBlockTypes = array(
        'Code',
    );
    protected $InlineTypes = array(
        '"' => array('SpecialCharacter'),
        '!' => array('Image'),
        '&' => array('SpecialCharacter'),
        '*' => array('Emphasis'),
        ':' => array('Url'),
        '<' => array('UrlTag', 'EmailTag', 'Markup', 'SpecialCharacter'),
        '>' => array('SpecialCharacter'),
        '[' => array('Link'),
        '_' => array('Emphasis'),
        '`' => array('Code'),
        '~' => array('Strikethrough'),
        '\\' => array('EscapeSequence'),
    );

    #
    # Lines
    #
    protected $inlineMarkerList = '!"*_&[:<>`~\\';

    # ~
    protected $DefinitionData;

    #
    # Blocks
    #
    protected $specialCharacters = array(
        '\\', '`', '*', '_', '{', '}', '[', ']', '(', ')', '>', '#', '+', '-', '.', '!', '|',
    );
    protected $StrongRegex = array(
        '*' => '/^[*]{2}((?:\\\\\*|[^*]|[*][^*]*[*])+?)[*]{2}(?![*])/s',
        '_' => '/^__((?:\\\\_|[^_]|_[^_]*_)+?)__(?!_)/us',
    );
    protected $EmRegex = array(
        '*' => '/^[*]((?:\\\\\*|[^*]|[*][*][^*]+?[*][*])+?)[*](?![*])/s',
        '_' => '/^_((?:\\\\_|[^_]|__[^_]*__)+?)_(?!_)\b/us',
    );

    #
    # Code
    protected $regexHtmlAttribute = '[a-zA-Z_:][\w:.-]*(?:\s*=\s*(?:[^"\'=<>`\s]+|"[^"]*"|\'[^\']*\'))?';
    protected $voidElements = array(
        'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source',
    );
    protected $textLevelElements = array(
        'a', 'br', 'bdo', 'abbr', 'blink', 'nextid', 'acronym', 'basefont',
        'b', 'em', 'big', 'cite', 'small', 'spacer', 'listing',
        'i', 'rp', 'del', 'code', 'strike', 'marquee',
        'q', 'rt', 'ins', 'font', 'strong',
        's', 'tt', 'kbd', 'mark',
        'u', 'xm', 'sub', 'nobr',
        'sup', 'ruby',
        'var', 'span',
        'wbr', 'time',
    );

    #
    # Comment

    static function instance($name = 'default')
    {
        if (isset(self::$instances[$name])) {
            return self::$instances[$name];
        }

        $instance = new static();

        self::$instances[$name] = $instance;

        return $instance;
    }

    function setBreaksEnabled($breaksEnabled)
    {
        $this->breaksEnabled = $breaksEnabled;

        return $this;
    }

    #
    # Fenced Code

    function setMarkupEscaped($markupEscaped)
    {
        $this->markupEscaped = $markupEscaped;

        return $this;
    }

    function setUrlsLinked($urlsLinked)
    {
        $this->urlsLinked = $urlsLinked;

        return $this;
    }

    public function line($text)
    {
        $markup = '';

        # $excerpt is based on the first occurrence of a marker

        while ($excerpt = strpbrk($text, $this->inlineMarkerList)) {
            $marker = $excerpt[0];

            $markerPosition = strpos($text, $marker);

            $Excerpt = array('text' => $excerpt, 'context' => $text);

            foreach ($this->InlineTypes[$marker] as $inlineType) {
                $Inline = $this->{'inline' . $inlineType}($Excerpt);

                if (!isset($Inline)) {
                    continue;
                }

                # makes sure that the inline belongs to "our" marker

                if (isset($Inline['position']) and $Inline['position'] > $markerPosition) {
                    continue;
                }

                # sets a default inline position

                if (!isset($Inline['position'])) {
                    $Inline['position'] = $markerPosition;
                }

                # the text that comes before the inline
                $unmarkedText = substr($text, 0, $Inline['position']);

                # compile the unmarked text
                $markup .= $this->unmarkedText($unmarkedText);

                # compile the inline
                $markup .= isset($Inline['markup']) ? $Inline['markup'] : $this->element($Inline['element']);

                # remove the examined text
                $text = substr($text, $Inline['position'] + $Inline['extent']);

                continue 2;
            }

            # the marker does not belong to an inline

            $unmarkedText = substr($text, 0, $markerPosition + 1);

            $markup .= $this->unmarkedText($unmarkedText);

            $text = substr($text, $markerPosition + 1);
        }

        $markup .= $this->unmarkedText($text);

        return $markup;
    }

    #
    # Header

    protected function unmarkedText($text)
    {
        if ($this->breaksEnabled) {
            $text = preg_replace('/[ ]*\n/', "<br />\n", $text);
        } else {
            $text = preg_replace('/(?:[ ][ ]+|[ ]*\\\\)\n/', "<br />\n", $text);
            $text = str_replace(" \n", "\n", $text);
        }

        return $text;
    }

    #
    # List

    protected function element(array $Element)
    {
        $markup = '<' . $Element['name'];

        if (isset($Element['attributes'])) {
            foreach ($Element['attributes'] as $name => $value) {
                if ($value === null) {
                    continue;
                }

                $markup .= ' ' . $name . '="' . $value . '"';
            }
        }

        if (isset($Element['text'])) {
            $markup .= '>';

            if (isset($Element['handler'])) {
                $markup .= $this->{$Element['handler']}($Element['text']);
            } else {
                $markup .= $Element['text'];
            }

            $markup .= '</' . $Element['name'] . '>';
        } else {
            $markup .= ' />';
        }

        return $markup;
    }

    function parse($text)
    {
        $markup = $this->text($text);

        return $markup;
    }

    #
    # Quote

    function text($text)
    {
        # make sure no definitions are set
        $this->DefinitionData = array();

        # standardize line breaks
        $text = str_replace(array("\r\n", "\r"), "\n", $text);

        # remove surrounding line breaks
        $text = trim($text, "\n");

        # split text into lines
        $lines = explode("\n", $text);

        # iterate through lines to identify blocks
        $markup = $this->lines($lines);

        # trim line breaks
        $markup = trim($markup, "\n");

        return $markup;
    }

    protected function blockCode($Line, $Block = null)
    {
        if (isset($Block) and !isset($Block['type']) and !isset($Block['interrupted'])) {
            return;
        }

        if ($Line['indent'] >= 4) {
            $text = substr($Line['body'], 4);

            $Block = array(
                'element' => array(
                    'name' => 'pre',
                    'handler' => 'element',
                    'text' => array(
                        'name' => 'code',
                        'text' => $text,
                    ),
                ),
            );

            return $Block;
        }
    }

    #
    # Rule

    protected function blockCodeContinue($Line, $Block)
    {
        if ($Line['indent'] >= 4) {
            if (isset($Block['interrupted'])) {
                $Block['element']['text']['text'] .= "\n";

                unset($Block['interrupted']);
            }

            $Block['element']['text']['text'] .= "\n";

            $text = substr($Line['body'], 4);

            $Block['element']['text']['text'] .= $text;

            return $Block;
        }
    }

    #
    # Setext

    protected function blockCodeComplete($Block)
    {
        $text = $Block['element']['text']['text'];

        $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');

        $Block['element']['text']['text'] = $text;

        return $Block;
    }

    #
    # Markup

    protected function blockComment($Line)
    {
        if ($this->markupEscaped) {
            return;
        }

        if (isset($Line['text'][3]) and $Line['text'][3] === '-' and $Line['text'][2] === '-' and $Line['text'][1] === '!') {
            $Block = array(
                'markup' => $Line['body'],
            );

            if (preg_match('/-->$/', $Line['text'])) {
                $Block['closed'] = true;
            }

            return $Block;
        }
    }

    protected function blockCommentContinue($Line, array $Block)
    {
        if (isset($Block['closed'])) {
            return;
        }

        $Block['markup'] .= "\n" . $Line['body'];

        if (preg_match('/-->$/', $Line['text'])) {
            $Block['closed'] = true;
        }

        return $Block;
    }

    #
    # Reference

    protected function blockFencedCode($Line)
    {
        if (preg_match('/^[' . $Line['text'][0] . ']{3,}[ ]*([\w-]+)?[ ]*$/', $Line['text'], $matches)) {
            $Element = array(
                'name' => 'code',
                'text' => '',
            );

            if (isset($matches[1])) {
                $class = 'language-' . $matches[1];

                $Element['attributes'] = array(
                    'class' => $class,
                );
            }

            $Block = array(
                'char' => $Line['text'][0],
                'element' => array(
                    'name' => 'pre',
                    'handler' => 'element',
                    'text' => $Element,
                ),
            );

            return $Block;
        }
    }

    #
    # Table

    protected function blockFencedCodeContinue($Line, $Block)
    {
        if (isset($Block['complete'])) {
            return;
        }

        if (isset($Block['interrupted'])) {
            $Block['element']['text']['text'] .= "\n";

            unset($Block['interrupted']);
        }

        if (preg_match('/^' . $Block['char'] . '{3,}[ ]*$/', $Line['text'])) {
            $Block['element']['text']['text'] = substr($Block['element']['text']['text'], 1);

            $Block['complete'] = true;

            return $Block;
        }

        $Block['element']['text']['text'] .= "\n" . $Line['body'];

        return $Block;
    }

    protected function blockFencedCodeComplete($Block)
    {
        $text = $Block['element']['text']['text'];

        $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');

        $Block['element']['text']['text'] = $text;

        return $Block;
    }

    #
    # ~
    #

    protected function blockHeader($Line)
    {
        if (isset($Line['text'][1])) {
            $level = 1;

            while (isset($Line['text'][$level]) and $Line['text'][$level] === '#') {
                $level++;
            }

            if ($level > 6) {
                return;
            }

            $text = trim($Line['text'], '# ');

            $Block = array(
                'element' => array(
                    'name' => 'h' . min(6, $level),
                    'text' => $text,
                    'handler' => 'line',
                ),
            );

            return $Block;
        }
    }

    #
    # Inline Elements
    #

    protected function blockList($Line)
    {
        list($name, $pattern) = $Line['text'][0] <= '-' ? array('ul', '[*+-]') : array('ol', '[0-9]+[.]');

        if (preg_match('/^(' . $pattern . '[ ]+)(.*)/', $Line['text'], $matches)) {
            $Block = array(
                'indent' => $Line['indent'],
                'pattern' => $pattern,
                'element' => array(
                    'name' => $name,
                    'handler' => 'elements',
                ),
            );

            if ($name === 'ol') {
                $listStart = stristr($matches[0], '.', true);

                if ($listStart !== '1') {
                    $Block['element']['attributes'] = array('start' => $listStart);
                }
            }

            $Block['li'] = array(
                'name' => 'li',
                'handler' => 'li',
                'text' => array(
                    $matches[2],
                ),
            );

            $Block['element']['text'] [] = &$Block['li'];

            return $Block;
        }
    }

    # ~

    protected function blockListContinue($Line, array $Block)
    {
        if ($Block['indent'] === $Line['indent'] and preg_match('/^' . $Block['pattern'] . '(?:[ ]+(.*)|$)/', $Line['text'], $matches)) {
            if (isset($Block['interrupted'])) {
                $Block['li']['text'] [] = '';

                unset($Block['interrupted']);
            }

            unset($Block['li']);

            $text = isset($matches[1]) ? $matches[1] : '';

            $Block['li'] = array(
                'name' => 'li',
                'handler' => 'li',
                'text' => array(
                    $text,
                ),
            );

            $Block['element']['text'] [] = &$Block['li'];

            return $Block;
        }

        if ($Line['text'][0] === '[' and $this->blockReference($Line)) {
            return $Block;
        }

        if (!isset($Block['interrupted'])) {
            $text = preg_replace('/^[ ]{0,4}/', '', $Line['body']);

            $Block['li']['text'] [] = $text;

            return $Block;
        }

        if ($Line['indent'] > 0) {
            $Block['li']['text'] [] = '';

            $text = preg_replace('/^[ ]{0,4}/', '', $Line['body']);

            $Block['li']['text'] [] = $text;

            unset($Block['interrupted']);

            return $Block;
        }
    }

    #
    # ~
    #

    protected function blockReference($Line)
    {
        if (preg_match('/^\[(.+?)\]:[ ]*<?(\S+?)>?(?:[ ]+["\'(](.+)["\')])?[ ]*$/', $Line['text'], $matches)) {
            $id = strtolower($matches[1]);

            $Data = array(
                'url' => $matches[2],
                'title' => null,
            );

            if (isset($matches[3])) {
                $Data['title'] = $matches[3];
            }

            $this->DefinitionData['Reference'][$id] = $Data;

            $Block = array(
                'hidden' => true,
            );

            return $Block;
        }
    }

    #
    # ~
    #

    protected function blockQuote($Line)
    {
        if (preg_match('/^>[ ]?(.*)/', $Line['text'], $matches)) {
            $Block = array(
                'element' => array(
                    'name' => 'blockquote',
                    'handler' => 'lines',
                    'text' => (array)$matches[1],
                ),
            );

            return $Block;
        }
    }

    protected function blockQuoteContinue($Line, array $Block)
    {
        if ($Line['text'][0] === '>' and preg_match('/^>[ ]?(.*)/', $Line['text'], $matches)) {
            if (isset($Block['interrupted'])) {
                $Block['element']['text'] [] = '';

                unset($Block['interrupted']);
            }

            $Block['element']['text'] [] = $matches[1];

            return $Block;
        }

        if (!isset($Block['interrupted'])) {
            $Block['element']['text'] [] = $Line['text'];

            return $Block;
        }
    }

    protected function blockRule($Line)
    {
        if (preg_match('/^([' . $Line['text'][0] . '])([ ]*\1){2,}[ ]*$/', $Line['text'])) {
            $Block = array(
                'element' => array(
                    'name' => 'hr'
                ),
            );

            return $Block;
        }
    }

    protected function blockSetextHeader($Line, array $Block = null)
    {
        if (!isset($Block) or isset($Block['type']) or isset($Block['interrupted'])) {
            return;
        }

        if (chop($Line['text'], $Line['text'][0]) === '') {
            $Block['element']['name'] = $Line['text'][0] === '=' ? 'h1' : 'h2';

            return $Block;
        }
    }

    protected function blockMarkup($Line)
    {
        if ($this->markupEscaped) {
            return;
        }

        if (preg_match('/^<(\w*)(?:[ ]*' . $this->regexHtmlAttribute . ')*[ ]*(\/)?>/', $Line['text'], $matches)) {
            $element = strtolower($matches[1]);

            if (in_array($element, $this->textLevelElements)) {
                return;
            }

            $Block = array(
                'name' => $matches[1],
                'depth' => 0,
                'markup' => $Line['text'],
            );

            $length = strlen($matches[0]);

            $remainder = substr($Line['text'], $length);

            if (trim($remainder) === '') {
                if (isset($matches[2]) or in_array($matches[1], $this->voidElements)) {
                    $Block['closed'] = true;

                    $Block['void'] = true;
                }
            } else {
                if (isset($matches[2]) or in_array($matches[1], $this->voidElements)) {
                    return;
                }

                if (preg_match('/<\/' . $matches[1] . '>[ ]*$/i', $remainder)) {
                    $Block['closed'] = true;
                }
            }

            return $Block;
        }
    }

    protected function blockMarkupContinue($Line, array $Block)
    {
        if (isset($Block['closed'])) {
            return;
        }

        if (preg_match('/^<' . $Block['name'] . '(?:[ ]*' . $this->regexHtmlAttribute . ')*[ ]*>/i', $Line['text'])) # open
        {
            $Block['depth']++;
        }

        if (preg_match('/(.*?)<\/' . $Block['name'] . '>[ ]*$/i', $Line['text'], $matches)) # close
        {
            if ($Block['depth'] > 0) {
                $Block['depth']--;
            } else {
                $Block['closed'] = true;
            }
        }

        if (isset($Block['interrupted'])) {
            $Block['markup'] .= "\n";

            unset($Block['interrupted']);
        }

        $Block['markup'] .= "\n" . $Line['body'];

        return $Block;
    }

    protected function blockTable($Line, array $Block = null)
    {
        if (!isset($Block) or isset($Block['type']) or isset($Block['interrupted'])) {
            return;
        }

        if (strpos($Block['element']['text'], '|') !== false and chop($Line['text'], ' -:|') === '') {
            $alignments = array();

            $divider = $Line['text'];

            $divider = trim($divider);
            $divider = trim($divider, '|');

            $dividerCells = explode('|', $divider);

            foreach ($dividerCells as $dividerCell) {
                $dividerCell = trim($dividerCell);

                if ($dividerCell === '') {
                    continue;
                }

                $alignment = null;

                if ($dividerCell[0] === ':') {
                    $alignment = 'left';
                }

                if (substr($dividerCell, -1) === ':') {
                    $alignment = $alignment === 'left' ? 'center' : 'right';
                }

                $alignments [] = $alignment;
            }

            # ~

            $HeaderElements = array();

            $header = $Block['element']['text'];

            $header = trim($header);
            $header = trim($header, '|');

            $headerCells = explode('|', $header);

            foreach ($headerCells as $index => $headerCell) {
                $headerCell = trim($headerCell);

                $HeaderElement = array(
                    'name' => 'th',
                    'text' => $headerCell,
                    'handler' => 'line',
                );

                if (isset($alignments[$index])) {
                    $alignment = $alignments[$index];

                    $HeaderElement['attributes'] = array(
                        'style' => 'text-align: ' . $alignment . ';',
                    );
                }

                $HeaderElements [] = $HeaderElement;
            }

            # ~

            $Block = array(
                'alignments' => $alignments,
                'identified' => true,
                'element' => array(
                    'name' => 'table',
                    'handler' => 'elements',
                ),
            );

            $Block['element']['text'] [] = array(
                'name' => 'thead',
                'handler' => 'elements',
            );

            $Block['element']['text'] [] = array(
                'name' => 'tbody',
                'handler' => 'elements',
                'text' => array(),
            );

            $Block['element']['text'][0]['text'] [] = array(
                'name' => 'tr',
                'handler' => 'elements',
                'text' => $HeaderElements,
            );

            return $Block;
        }
    }

    protected function blockTableContinue($Line, array $Block)
    {
        if (isset($Block['interrupted'])) {
            return;
        }

        if ($Line['text'][0] === '|' or strpos($Line['text'], '|')) {
            $Elements = array();

            $row = $Line['text'];

            $row = trim($row);
            $row = trim($row, '|');

            preg_match_all('/(?:(\\\\[|])|[^|`]|`[^`]+`|`)+/', $row, $matches);

            foreach ($matches[0] as $index => $cell) {
                $cell = trim($cell);

                $Element = array(
                    'name' => 'td',
                    'handler' => 'line',
                    'text' => $cell,
                );

                if (isset($Block['alignments'][$index])) {
                    $Element['attributes'] = array(
                        'style' => 'text-align: ' . $Block['alignments'][$index] . ';',
                    );
                }

                $Elements [] = $Element;
            }

            $Element = array(
                'name' => 'tr',
                'handler' => 'elements',
                'text' => $Elements,
            );

            $Block['element']['text'][1]['text'] [] = $Element;

            return $Block;
        }
    }

    protected function inlineCode($Excerpt)
    {
        $marker = $Excerpt['text'][0];

        if (preg_match('/^(' . $marker . '+)[ ]*(.+?)[ ]*(?<!' . $marker . ')\1(?!' . $marker . ')/s', $Excerpt['text'], $matches)) {
            $text = $matches[2];
            $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
            $text = preg_replace("/[ ]*\n/", ' ', $text);

            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'code',
                    'text' => $text,
                ),
            );
        }
    }

    protected function inlineEmailTag($Excerpt)
    {
        if (strpos($Excerpt['text'], '>') !== false and preg_match('/^<((mailto:)?\S+?@\S+?)>/i', $Excerpt['text'], $matches)) {
            $url = $matches[1];

            if (!isset($matches[2])) {
                $url = 'mailto:' . $url;
            }

            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'a',
                    'text' => $matches[1],
                    'attributes' => array(
                        'href' => $url,
                    ),
                ),
            );
        }
    }

    protected function inlineEmphasis($Excerpt)
    {
        if (!isset($Excerpt['text'][1])) {
            return;
        }

        $marker = $Excerpt['text'][0];

        if ($Excerpt['text'][1] === $marker and preg_match($this->StrongRegex[$marker], $Excerpt['text'], $matches)) {
            $emphasis = 'strong';
        } elseif (preg_match($this->EmRegex[$marker], $Excerpt['text'], $matches)) {
            $emphasis = 'em';
        } else {
            return;
        }

        return array(
            'extent' => strlen($matches[0]),
            'element' => array(
                'name' => $emphasis,
                'handler' => 'line',
                'text' => $matches[1],
            ),
        );
    }

    # ~

    protected function inlineEscapeSequence($Excerpt)
    {
        if (isset($Excerpt['text'][1]) and in_array($Excerpt['text'][1], $this->specialCharacters)) {
            return array(
                'markup' => $Excerpt['text'][1],
                'extent' => 2,
            );
        }
    }

    #
    # Handlers
    #

    protected function inlineImage($Excerpt)
    {
        if (!isset($Excerpt['text'][1]) or $Excerpt['text'][1] !== '[') {
            return;
        }

        $Excerpt['text'] = substr($Excerpt['text'], 1);

        $Link = $this->inlineLink($Excerpt);

        if ($Link === null) {
            return;
        }

        $Inline = array(
            'extent' => $Link['extent'] + 1,
            'element' => array(
                'name' => 'img',
                'attributes' => array(
                    'src' => $Link['element']['attributes']['href'],
                    'alt' => $Link['element']['text'],
                ),
            ),
        );

        $Inline['element']['attributes'] += $Link['element']['attributes'];

        unset($Inline['element']['attributes']['href']);

        return $Inline;
    }

    protected function inlineLink($Excerpt)
    {
        $Element = array(
            'name' => 'a',
            'handler' => 'line',
            'text' => null,
            'attributes' => array(
                'href' => null,
                'title' => null,
            ),
        );

        $extent = 0;

        $remainder = $Excerpt['text'];

        if (preg_match('/\[((?:[^][]++|(?R))*+)\]/', $remainder, $matches)) {
            $Element['text'] = $matches[1];

            $extent += strlen($matches[0]);

            $remainder = substr($remainder, $extent);
        } else {
            return;
        }

        if (preg_match('/^[(]\s*+((?:[^ ()]++|[(][^ )]+[)])++)(?:[ ]+("[^"]*"|\'[^\']*\'))?\s*[)]/', $remainder, $matches)) {
            $Element['attributes']['href'] = $matches[1];

            if (isset($matches[2])) {
                $Element['attributes']['title'] = substr($matches[2], 1, -1);
            }

            $extent += strlen($matches[0]);
        } else {
            if (preg_match('/^\s*\[(.*?)\]/', $remainder, $matches)) {
                $definition = strlen($matches[1]) ? $matches[1] : $Element['text'];
                $definition = strtolower($definition);

                $extent += strlen($matches[0]);
            } else {
                $definition = strtolower($Element['text']);
            }

            if (!isset($this->DefinitionData['Reference'][$definition])) {
                return;
            }

            $Definition = $this->DefinitionData['Reference'][$definition];

            $Element['attributes']['href'] = $Definition['url'];
            $Element['attributes']['title'] = $Definition['title'];
        }

        $Element['attributes']['href'] = str_replace(array('&', '<'), array('&amp;', '&lt;'), $Element['attributes']['href']);

        return array(
            'extent' => $extent,
            'element' => $Element,
        );
    }

    # ~

    protected function inlineMarkup($Excerpt)
    {
        if ($this->markupEscaped or strpos($Excerpt['text'], '>') === false) {
            return;
        }

        if ($Excerpt['text'][1] === '/' and preg_match('/^<\/\w*[ ]*>/s', $Excerpt['text'], $matches)) {
            return array(
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            );
        }

        if ($Excerpt['text'][1] === '!' and preg_match('/^<!---?[^>-](?:-?[^-])*-->/s', $Excerpt['text'], $matches)) {
            return array(
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            );
        }

        if ($Excerpt['text'][1] !== ' ' and preg_match('/^<\w*(?:[ ]*' . $this->regexHtmlAttribute . ')*[ ]*\/?>/s', $Excerpt['text'], $matches)) {
            return array(
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            );
        }
    }

    #
    # Deprecated Methods
    #

    protected function inlineSpecialCharacter($Excerpt)
    {
        if ($Excerpt['text'][0] === '&' and !preg_match('/^&#?\w+;/', $Excerpt['text'])) {
            return array(
                'markup' => '&amp;',
                'extent' => 1,
            );
        }

        $SpecialCharacter = array('>' => 'gt', '<' => 'lt', '"' => 'quot');

        if (isset($SpecialCharacter[$Excerpt['text'][0]])) {
            return array(
                'markup' => '&' . $SpecialCharacter[$Excerpt['text'][0]] . ';',
                'extent' => 1,
            );
        }
    }

    #
    # Static Methods
    #

    protected function inlineStrikethrough($Excerpt)
    {
        if (!isset($Excerpt['text'][1])) {
            return;
        }

        if ($Excerpt['text'][1] === '~' and preg_match('/^~~(?=\S)(.+?)(?<=\S)~~/', $Excerpt['text'], $matches)) {
            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'del',
                    'text' => $matches[1],
                    'handler' => 'line',
                ),
            );
        }
    }

    protected function inlineUrl($Excerpt)
    {
        if ($this->urlsLinked !== true or !isset($Excerpt['text'][2]) or $Excerpt['text'][2] !== '/') {
            return;
        }

        if (preg_match('/\bhttps?:[\/]{2}[^\s<]+\b\/*/ui', $Excerpt['context'], $matches, PREG_OFFSET_CAPTURE)) {
            $Inline = array(
                'extent' => strlen($matches[0][0]),
                'position' => $matches[0][1],
                'element' => array(
                    'name' => 'a',
                    'text' => $matches[0][0],
                    'attributes' => array(
                        'href' => $matches[0][0],
                    ),
                ),
            );

            return $Inline;
        }
    }

    #
    # Fields
    #

    protected function inlineUrlTag($Excerpt)
    {
        if (strpos($Excerpt['text'], '>') !== false and preg_match('/^<(\w+:\/{2}[^ >]+)>/i', $Excerpt['text'], $matches)) {
            $url = str_replace(array('&', '<'), array('&amp;', '&lt;'), $matches[1]);

            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'a',
                    'text' => $url,
                    'attributes' => array(
                        'href' => $url,
                    ),
                ),
            );
        }
    }

    #
    # Read-Only

    protected function elements(array $Elements)
    {
        $markup = '';

        foreach ($Elements as $Element) {
            $markup .= "\n" . $this->element($Element);
        }

        $markup .= "\n";

        return $markup;
    }

    protected function li($lines)
    {
        $markup = $this->lines($lines);

        $trimmedMarkup = trim($markup);

        if (!in_array('', $lines) and substr($trimmedMarkup, 0, 3) === '<p>') {
            $markup = $trimmedMarkup;
            $markup = substr($markup, 3);

            $position = strpos($markup, "</p>");

            $markup = substr_replace($markup, '', $position, 4);
        }

        return $markup;
    }

    protected function lines(array $lines)
    {
        $CurrentBlock = null;

        foreach ($lines as $line) {
            if (chop($line) === '') {
                if (isset($CurrentBlock)) {
                    $CurrentBlock['interrupted'] = true;
                }

                continue;
            }

            if (strpos($line, "\t") !== false) {
                $parts = explode("\t", $line);

                $line = $parts[0];

                unset($parts[0]);

                foreach ($parts as $part) {
                    $shortage = 4 - mb_strlen($line, 'utf-8') % 4;

                    $line .= str_repeat(' ', $shortage);
                    $line .= $part;
                }
            }

            $indent = 0;

            while (isset($line[$indent]) and $line[$indent] === ' ') {
                $indent++;
            }

            $text = $indent > 0 ? substr($line, $indent) : $line;

            # ~

            $Line = array('body' => $line, 'indent' => $indent, 'text' => $text);

            # ~

            if (isset($CurrentBlock['continuable'])) {
                $Block = $this->{'block' . $CurrentBlock['type'] . 'Continue'}($Line, $CurrentBlock);

                if (isset($Block)) {
                    $CurrentBlock = $Block;

                    continue;
                } else {
                    if ($this->isBlockCompletable($CurrentBlock['type'])) {
                        $CurrentBlock = $this->{'block' . $CurrentBlock['type'] . 'Complete'}($CurrentBlock);
                    }
                }
            }

            # ~

            $marker = $text[0];

            # ~

            $blockTypes = $this->unmarkedBlockTypes;

            if (isset($this->BlockTypes[$marker])) {
                foreach ($this->BlockTypes[$marker] as $blockType) {
                    $blockTypes [] = $blockType;
                }
            }

            #
            # ~

            foreach ($blockTypes as $blockType) {
                $Block = $this->{'block' . $blockType}($Line, $CurrentBlock);

                if (isset($Block)) {
                    $Block['type'] = $blockType;

                    if (!isset($Block['identified'])) {
                        $Blocks [] = $CurrentBlock;

                        $Block['identified'] = true;
                    }

                    if ($this->isBlockContinuable($blockType)) {
                        $Block['continuable'] = true;
                    }

                    $CurrentBlock = $Block;

                    continue 2;
                }
            }

            # ~

            if (isset($CurrentBlock) and !isset($CurrentBlock['type']) and !isset($CurrentBlock['interrupted'])) {
                $CurrentBlock['element']['text'] .= "\n" . $text;
            } else {
                $Blocks [] = $CurrentBlock;

                $CurrentBlock = $this->paragraph($Line);

                $CurrentBlock['identified'] = true;
            }
        }

        # ~

        if (isset($CurrentBlock['continuable']) and $this->isBlockCompletable($CurrentBlock['type'])) {
            $CurrentBlock = $this->{'block' . $CurrentBlock['type'] . 'Complete'}($CurrentBlock);
        }

        # ~

        $Blocks [] = $CurrentBlock;

        unset($Blocks[0]);

        # ~

        $markup = '';

        foreach ($Blocks as $Block) {
            if (isset($Block['hidden'])) {
                continue;
            }

            $markup .= "\n";
            $markup .= isset($Block['markup']) ? $Block['markup'] : $this->element($Block['element']);
        }

        $markup .= "\n";

        # ~

        return $markup;
    }

    protected function isBlockCompletable($Type)
    {
        return method_exists($this, 'block' . $Type . 'Complete');
    }

    protected function isBlockContinuable($Type)
    {
        return method_exists($this, 'block' . $Type . 'Continue');
    }

    protected function paragraph($Line)
    {
        $Block = array(
            'element' => array(
                'name' => 'p',
                'text' => $Line['text'],
                'handler' => 'line',
            ),
        );

        return $Block;
    }
}

function getPluralForm($n, $forms)
{
    $index = ($n % 10 == 1 && $n % 100 != 11 ? 0 : $n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? 1 : 2);

    return $forms[$index];
}

function ext($file)
{
    return pathinfo($file, PATHINFO_EXTENSION);
}

function getTitleHtmlPage($file)
{
    $content = file_get_contents($file);
    if (preg_match('#<title>(.*)</title>#', $content, $result)) {
        return htmlspecialchars(strip_tags($result[1]));
    } else {
        return '';
    }
}

const DS = DIRECTORY_SEPARATOR;
const DIR_DOCS = 'docs';
const INDEX_DOC = 'README.md';

$TITLE = $_SERVER['HTTP_HOST'];

$HTML = null;
$docList = null;
$pages = [];
$files = [];
$dirs = [];

if (isset($_GET['doc'])) {
    $doc = ($_GET['doc'] === '')
        ? INDEX_DOC
        : trim(str_replace(['..', '\\'], '', $_GET['doc']), '/');

    if (empty($doc) or ext($doc) !== 'md') {
        echo '<head><meta http-equiv="refresh" content="3;URL=?doc=">';
        echo '<body><h1>Invalid request. Redirect in 3 seconds...</h1>';
        exit;
    }

    $pattern = '~\[[^\\]]+\](\(docs\\/)[^)]+\.md\)~';
    $pattern2 = '~\[[^\\]]*\](\(\\.\\.\\/source\\/)[^)]+\)~';
    $docFile = __DIR__ . DS . DIR_DOCS . DS . $doc;

    if (file_exists($docFile) && is_readable($docFile)) {
        $markdown = file_get_contents($docFile);

        $markdown = preg_replace_callback($pattern, function ($match) {
            return str_replace($match[1], '(?doc=', $match[0]);
        }, $markdown);

        preg_match($pattern2, $markdown, $matches);

        $markdown = preg_replace_callback($pattern2, function ($match) {
            return str_replace($match[1], '(design/', $match[0]);
        }, $markdown);
//    echo '<body><pre>';
//    print_r($pattern2);
//    print_r($matches);
//    echo $markdown;
//    exit;
        $parser = new \Parsedown();
        $HTML = $parser->text($markdown);
        $TITLE = strtoupper($doc) . ' :: ' . $TITLE;
    } else {
        $docList = glob(__DIR__ . DS . DIR_DOCS . DS . '*.md');
        sort($docList);
        $TITLE = 'DOCS :: ' . $TITLE;
    }
} else {
    $pages = glob(__DIR__ . DS . '*.html');
    sort($pages);

    $files = array_diff(glob(__DIR__ . DS . '*.*'), $pages);
    sort($files);

    $dirs = glob(__DIR__ . DS . '*', GLOB_ONLYDIR);
    sort($dirs);
}

$DOC_EXISTS = is_dir(__DIR__ . DS . DIR_DOCS);

?><!DOCTYPE html>
<html>
<head>
    <title><?= $TITLE ?></title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

   <style>
        :root {
            --color-main: #333e48;
            --color-pink: #ee6e73;
            --color-teal: #009688;
            --color-blue-grey-darken-1: #546e7a;
            --color-deep-orange-darken-1: #f4511e;
            --color-link: #389ac6;
            --color-link-hover: #266485;
            --icon-html: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCAzMTguMTg4IDMxOC4xODgiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDMxOC4xODggMzE4LjE4ODsiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnPjxwb2x5Z29uIHN0eWxlPSJmaWxsOiNFNEU0RTQ7IiBwb2ludHM9IjIyNy4zMjEsNy41IDQwLjM0Miw3LjUgNDAuMzQyLDMxMC42ODggMjc3Ljg0NiwzMTAuNjg4IDI3Ny44NDYsNTguMDI1ICIvPjxwb2x5Z29uIHN0eWxlPSJmaWxsOiMwMDc5MzQ7IiBwb2ludHM9IjIzNS4xNCwzMi43NjMgNDAuMzQyLDMyLjc2MyA0MC4zNDIsNy41IDIyNy4zMjEsNy41ICIvPjxnPjxwYXRoIHN0eWxlPSJmaWxsOiMwMDc5MzQ7IiBkPSJNMTMzLjYxNiwxNjIuMTc5TDg0LjY5LDE0MC40NXYtOC4zMjNsNDguOTI1LTI0LjQ4N3YxNC4zNzNsLTMwLjAwNCwxMy42OTRsMzAuMDA0LDEyLjE5NUwxMzMuNjE2LDE2Mi4xNzlMMTMzLjYxNiwxNjIuMTc5eiIvPjxwYXRoIHN0eWxlPSJmaWxsOiMwMDc5MzQ7IiBkPSJNMTgwLjg5NSw5OS4wMjZsLTI2LjM3NCw3Mi43MzRoLTE2Ljc0NGwyNi4zNzQtNzIuNzM0QzE2NC4xNTEsOTkuMDI2LDE4MC44OTUsOTkuMDI2LDE4MC44OTUsOTkuMDI2eiIvPjxwYXRoIHN0eWxlPSJmaWxsOiMwMDc5MzQ7IiBkPSJNMTg0LjU3MiwxNDcuOTAybDMwLjAwNC0xMi4xOTVsLTMwLjAwNC0xMy42OTRWMTA3LjY0bDQ4LjkyNSwyNC40ODd2OC4zMjNsLTQ4LjkyNSwyMS43MjlWMTQ3LjkwMnoiLz48L2c+PHBvbHlnb24gc3R5bGU9ImZpbGw6I0QxRDNEMzsiIHBvaW50cz0iMjI3LjMyMSw1OC4wMjUgMjc3Ljg0Niw1OC4wMjUgMjI3LjMyMSw3LjUgIi8+PHBhdGggc3R5bGU9ImZpbGw6IzMzM0U0ODsiIGQ9Ik0xMDAuODgyLDI1NC40MThIODcuNDM1di0xNS44NzlINzUuODA0djQyLjgzMmgxMS42MzFWMjYzLjkxaDEzLjQ0N3YxNy40NjFoMTEuNjMxdi00Mi44MzJoLTExLjYzMUMxMDAuODgyLDIzOC41MzksMTAwLjg4MiwyNTQuNDE4LDEwMC44ODIsMjU0LjQxOHogTTI4My4xNDksNTIuNzIyTDIzMi42MjUsMi4xOTdDMjMxLjIxOCwwLjc5LDIyOS4zMTEsMCwyMjcuMzIxLDBINDAuMzQyYy00LjE0MiwwLTcuNSwzLjM1OC03LjUsNy41djMwMy4xODhjMCw0LjE0MiwzLjM1OCw3LjUsNy41LDcuNWgyMzcuNTA0YzQuMTQyLDAsNy41LTMuMzU4LDcuNS03LjVWNTguMDI1QzI4NS4zNDYsNTYuMDM2LDI4NC41NTYsNTQuMTI5LDI4My4xNDksNTIuNzIyeiBNMjM0LjgyMSwyNS42MDZsMjQuOTE4LDI0LjkxOWgtMjQuOTE4TDIzNC44MjEsMjUuNjA2TDIzNC44MjEsMjUuNjA2eiBNNDcuODQyLDE1aDE3MS45Nzl2MTAuMjYzSDQ3Ljg0MlYxNXogTTI3MC4zNDYsMzAzLjE4OEg0Ny44NDJWNDAuMjYzaDE3MS45Nzl2MTcuNzYzYzAsNC4xNDIsMy4zNTgsNy41LDcuNSw3LjVoNDMuMDI0djIzNy42NjJIMjcwLjM0NnogTTIyNi44LDIzOC41MzloLTExLjU3MnY0Mi44MzJoMjguMDY2di05LjM0NkgyMjYuOFYyMzguNTM5eiBNMTE4LjYzNiwyNDguMDAyaDEwLjQ1OXYzMy4zNjloMTEuNTcydi0zMy4zNjloMTAuNDN2LTkuNDYzaC0zMi40NjFMMTE4LjYzNiwyNDguMDAyTDExOC42MzYsMjQ4LjAwMnogTTE4MS44LDI2OC45NDloLTAuMjM0bC04LjkzNi0zMC40MWgtMTUuNDF2NDIuODMyaDEwLjI1NHYtMTguNjA0YzAtMi45MS0wLjIwNS02Ljk5Mi0wLjYxNS0xMi4yNDZoMC4yNjRsOC43NiwzMC44NWgxMS4wMTZsOC42NDMtMzAuNzkxaDAuMjY0Yy0wLjIzNCw0LjY0OC0wLjM2Niw3LjU2OS0wLjM5Niw4Ljc2Yy0wLjAyOSwxLjE5MS0wLjA0NCwyLjI3NS0wLjA0NCwzLjI1MnYxOC43NzloMTAuNjM1di00Mi44MzJIMTkwLjU2TDE4MS44LDI2OC45NDl6Ii8+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjwvc3ZnPg==);
            --icon-folder: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIj8+PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNjAgNjAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDYwIDYwOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4IiBjbGFzcz0iIj48Zz48Zz48cGF0aCBkPSJNMTQsMjMuNWMtMC4yNTQsMC0wLjQ3OSwwLjE3Mi0wLjU0NSwwLjQxN0wyLDUyLjV2MWMwLDAuNzM0LTAuMDQ3LDEsMC41NjUsMWg0NC43NTljMS4xNTYsMCwyLjE3NC0wLjc3OSwyLjQ1LTEuODEzICAgTDYwLDI0LjVjMCwwLDAtMC42MjUsMC0xSDE0eiIgZGF0YS1vcmlnaW5hbD0iIzAwMDAwMCIgY2xhc3M9ImFjdGl2ZS1wYXRoIiBkYXRhLW9sZF9jb2xvcj0iIzAwMDAwMCIgZmlsbD0iIzMzM0U0OCIvPjxwYXRoIGQ9Ik0xMi43MzEsMjEuNUg1M2gxdi02LjI2OGMwLTEuNTA3LTEuMjI2LTIuNzMyLTIuNzMyLTIuNzMySDI2LjUxNWwtNS03SDIuNzMyQzEuMjI2LDUuNSwwLDYuNzI2LDAsOC4yMzJ2NDEuNzk2ICAgbDEwLjI4Mi0yNi43MTdDMTAuNTU3LDIyLjI3OSwxMS41NzUsMjEuNSwxMi43MzEsMjEuNXoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiIGZpbGw9IiMzMzNFNDgiLz48L2c+PC9nPiA8L3N2Zz4=);
            --icon-ico: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCAzMTguMTg4IDMxOC4xODgiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDMxOC4xODggMzE4LjE4ODsiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnPjxwb2x5Z29uIHN0eWxlPSJmaWxsOiNFOEU4RTg7IiBwb2ludHM9IjIyNy4zMjEsNy41IDQwLjM0Miw3LjUgNDAuMzQyLDMxMC42ODggMjc3Ljg0NiwzMTAuNjg4IDI3Ny44NDYsNTguMDI1ICIvPjxnPjxwYXRoIHN0eWxlPSJmaWxsOiMwMDRBOTQ7IiBkPSJNMjE0LjM0MSwxNjcuOTY3YzAsMTAuODUzLTguODgsMTkuNzMyLTE5LjczMiwxOS43MzJoLTcxLjAzYy0xMC44NTMsMC0xOS43MzItOC44OC0xOS43MzItMTkuNzMydi03MS4wM2MwLTEwLjg1Myw4Ljg4LTE5LjczMiwxOS43MzItMTkuNzMyaDcxLjAzYzEwLjg1MywwLDE5LjczMiw4Ljg4LDE5LjczMiwxOS43MzJWMTY3Ljk2N3oiLz48cG9seWdvbiBzdHlsZT0iZmlsbDojRkZGRkZGOyIgcG9pbnRzPSIxNzUuNTI2LDExMy4wMjcgMTc1LjUyNiwxMDIuNTczIDE0Mi42NTksMTAyLjU3MyAxNDIuNjU5LDExMy4wMjcgMTUzLjg2NiwxMTMuMDI3IDE1My44NjYsMTUxLjg3NSAxNDIuNjU5LDE1MS44NzUgMTQyLjY1OSwxNjIuMzI5IDE3NS41MjYsMTYyLjMyOSAxNzUuNTI2LDE1MS44NzUgMTY0LjMxOSwxNTEuODc1IDE2NC4zMTksMTEzLjAyNyAiLz48L2c+PHBvbHlnb24gc3R5bGU9ImZpbGw6IzAwNEE5NDsiIHBvaW50cz0iMjM1LjE0LDMyLjc2MyA0MC4zNDIsMzIuNzYzIDQwLjM0Miw3LjUgMjI3LjMyMSw3LjUgIi8+PHBvbHlnb24gc3R5bGU9ImZpbGw6I0QxRDNEMzsiIHBvaW50cz0iMjI3LjMyMSw1OC4wMjUgMjc3Ljg0Niw1OC4wMjUgMjI3LjMyMSw3LjUgIi8+PHBhdGggc3R5bGU9ImZpbGw6IzMzM0U0ODsiIGQ9Ik0xOTEuODg3LDIzMi42MzdjLTcuNjEzLDAtMTMuNDE3LDIuMTA2LTE3LjQwOSw2LjMxOGMtMy45OTMsNC4yMTEtNS45ODksMTAuMzEtNS45ODksMTguMjk1YzAsOC4wNzMsMi4wMDcsMTQuMjIyLDYuMDIxLDE4LjQ0M2M0LjAxNSw0LjIyMyw5Ljc4NSw2LjMzNCwxNy4zMTIsNi4zMzRjNy42MzYsMCwxMy40MzQtMi4xLDE3LjM5NC02LjMwMXM1Ljk0LTEwLjMzOCw1Ljk0LTE4LjQxYzAtOC4wOTYtMS45Ny0xNC4yMzItNS45MDctMTguNDExQzIwNS4zMSwyMzQuNzI3LDE5OS41MjIsMjMyLjYzNywxOTEuODg3LDIzMi42Mzd6IE0xOTkuMTIzLDI2Ny44NWMtMS41ODYsMi4yNTQtNC4wMiwzLjM4MS03LjMwMiwzLjM4MWMtNi40NTQsMC05LjY4MS00LjYzOS05LjY4MS0xMy45MTRjMC05LjM2NCwzLjI0OS0xNC4wNDcsOS43NDYtMTQuMDQ3YzMuMTk0LDAsNS41OTYsMS4xNDUsNy4yMDQsMy40M2MxLjYwNywyLjI4NywyLjQxMiw1LjgyNSwyLjQxMiwxMC42MTdDMjAxLjUwMywyNjIuMDg2LDIwMC43MDksMjY1LjU5OCwxOTkuMTIzLDI2Ny44NXogTTI4My4xNDksNTIuNzIzTDIzMi42MjUsMi4xOTdDMjMxLjIxOCwwLjc5LDIyOS4zMTEsMCwyMjcuMzIxLDBINDAuMzQyYy00LjE0MywwLTcuNSwzLjM1OC03LjUsNy41djMwMy4xODhjMCw0LjE0MywzLjM1Nyw3LjUsNy41LDcuNWgyMzcuNTA0YzQuMTQzLDAsNy41LTMuMzU3LDcuNS03LjVWNTguMDI1QzI4NS4zNDYsNTYuMDM2LDI4NC41NTYsNTQuMTI5LDI4My4xNDksNTIuNzIzeiBNMjM0LjgyMSwyNS42MDZsMjQuOTE4LDI0LjkxOWgtMjQuOTE4TDIzNC44MjEsMjUuNjA2TDIzNC44MjEsMjUuNjA2eiBNNDcuODQyLDE1aDE3MS45Nzl2MTAuMjYzSDQ3Ljg0MlYxNXogTTI3MC4zNDYsMzAzLjE4OEg0Ny44NDJWNDAuMjYzaDE3MS45Nzl2MTcuNzYzYzAsNC4xNDMsMy4zNTcsNy41LDcuNSw3LjVoNDMuMDI0djIzNy42NjJIMjcwLjM0NnogTTE0OC4wMTEsMjQzLjMzNmMyLjAzNCwwLDMuOTM4LDAuMjg0LDUuNzEsMC44NTRjMS43NzIsMC41NjgsMy41NDQsMS4yNjksNS4zMTYsMi4xbDMuOTcxLTEwLjIwNmMtNC43MjYtMi4yNTMtOS42ODEtMy4zOC0xNC44NjYtMy4zOGMtNC43NDgsMC04Ljg3NywxLjAxMi0xMi4zODksMy4wMzVzLTYuMjAzLDQuOTE4LTguMDczLDguNjhjLTEuODcsMy43NjQtMi44MDYsOC4xMjktMi44MDYsMTMuMDk1YzAsNy45NDIsMS45MzEsMTQuMDEzLDUuNzkzLDE4LjIxNGMzLjg2LDQuMjAxLDkuNDEyLDYuMzAxLDE2LjY1NCw2LjMwMWM1LjA1NCwwLDkuNTgzLTAuODg2LDEzLjU4Ni0yLjY1OFYyNjguNDFjLTIuMDEzLDAuODMyLTQuMDE1LDEuNTQzLTYuMDA1LDIuMTMzYy0xLjk5MSwwLjU5MS00LjA0OCwwLjg4Ny02LjE3LDAuODg3Yy03LjAyMiwwLTEwLjUzNC00LjYxNi0xMC41MzQtMTMuODVjMC00LjQ0MSwwLjg2My03LjkyNiwyLjU5Mi0xMC40NTJTMTQ0LjkyNSwyNDMuMzM2LDE0OC4wMTEsMjQzLjMzNnogTTEwMy4yNDcsMjgxLjM3MWgxMy4wMjl2LTQ3Ljk3OWgtMTMuMDI5VjI4MS4zNzF6Ii8+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjwvc3ZnPg==);
            --icon-txt: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCAzMTguMTg4IDMxOC4xODgiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDMxOC4xODggMzE4LjE4ODsiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnPjxwb2x5Z29uIHN0eWxlPSJmaWxsOiNFOEU4RTg7IiBwb2ludHM9IjIyNy4zMjEsNy41IDQwLjM0Miw3LjUgNDAuMzQyLDMxMC42ODggMjc3Ljg0NiwzMTAuNjg4IDI3Ny44NDYsNTguMDI1ICIvPjxnPjxyZWN0IHg9IjEwMC4zMTciIHk9IjE3My44NyIgc3R5bGU9ImZpbGw6I0QxRDNEMzsiIHdpZHRoPSI0OS41NDMiIGhlaWdodD0iMTIuODY1Ii8+PHJlY3QgeD0iMTAwLjMxNyIgeT0iMTQ5LjIyOSIgc3R5bGU9ImZpbGw6I0QxRDNEMzsiIHdpZHRoPSIxMDcuNTQzIiBoZWlnaHQ9IjEyLjg2NSIvPjxyZWN0IHg9IjEwMC4zMTciIHk9IjEyNC41ODciIHN0eWxlPSJmaWxsOiNEMUQzRDM7IiB3aWR0aD0iMTE3LjU1MSIgaGVpZ2h0PSIxMi44NjUiLz48cmVjdCB4PSIxMDAuMzE3IiB5PSI5OS45NDUiIHN0eWxlPSJmaWxsOiNEMUQzRDM7IiB3aWR0aD0iODIuMjA5IiBoZWlnaHQ9IjEyLjg2NSIvPjxyZWN0IHg9IjEwMC4zMTciIHk9Ijc1LjMwNCIgc3R5bGU9ImZpbGw6I0QxRDNEMzsiIHdpZHRoPSIxMTcuNTUxIiBoZWlnaHQ9IjEyLjg2NSIvPjwvZz48cG9seWdvbiBzdHlsZT0iZmlsbDojQTRBOUFEOyIgcG9pbnRzPSIyMzUuMTQsMzIuNzYzIDQwLjM0MiwzMi43NjMgNDAuMzQyLDcuNSAyMjcuMzIxLDcuNSAiLz48cG9seWdvbiBzdHlsZT0iZmlsbDojRDFEM0QzOyIgcG9pbnRzPSIyMjcuMzIxLDU4LjAyNSAyNzcuODQ2LDU4LjAyNSAyMjcuMzIxLDcuNSAiLz48cGF0aCBzdHlsZT0iZmlsbDojMzMzRTQ4OyIgZD0iTTk2LjAxMSwyNDMuOTkyaDExLjcxNnYzNy4zNzloMTIuOTYzdi0zNy4zNzloMTEuNjgzdi0xMC42MDFIOTYuMDExVjI0My45OTJ6IE0yODMuMTQ5LDUyLjcyM0wyMzIuNjI1LDIuMTk3QzIzMS4yMTgsMC43OSwyMjkuMzExLDAsMjI3LjMyMSwwSDQwLjM0MmMtNC4xNDMsMC03LjUsMy4zNTgtNy41LDcuNXYzMDMuMTg4YzAsNC4xNDMsMy4zNTcsNy41LDcuNSw3LjVoMjM3LjUwNGM0LjE0MywwLDcuNS0zLjM1Nyw3LjUtNy41VjU4LjAyNUMyODUuMzQ2LDU2LjAzNiwyODQuNTU2LDU0LjEyOSwyODMuMTQ5LDUyLjcyM3ogTTIzNC44MjEsMjUuNjA2bDI0LjkxOCwyNC45MTloLTI0LjkxOEwyMzQuODIxLDI1LjYwNkwyMzQuODIxLDI1LjYwNnogTTQ3Ljg0MiwxNWgxNzEuOTc5djEwLjI2M0g0Ny44NDJWMTV6IE0yNzAuMzQ2LDMwMy4xODhINDcuODQyVjQwLjI2M2gxNzEuOTc5djE3Ljc2M2MwLDQuMTQzLDMuMzU3LDcuNSw3LjUsNy41aDQzLjAyNHYyMzcuNjYySDI3MC4zNDZ6IE0xODQuMzIzLDI0My45OTJoMTEuNzE2djM3LjM3OWgxMi45NjN2LTM3LjM3OWgxMS42ODN2LTEwLjYwMWgtMzYuMzYxdjEwLjYwMUgxODQuMzIzeiBNMTgxLjMwNCwyMzMuMzkyaC0xNC44MDFsLTguMzM2LDE0LjgzNGwtOC42NjQtMTQuODM0SDEzNS4xM2wxNC45NjUsMjMuNDMzbC0xNS45MTcsMjQuNTQ3aDE0LjY2OWw5LjI1Ni0xNC45OThsOS4zNTIsMTQuOTk4aDE0Ljk5OGwtMTYuMzQ0LTIzLjUzTDE4MS4zMDQsMjMzLjM5MnoiLz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PC9zdmc+);
            --icon-bin: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjxzdmcgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDMxOC4xODggMzE4LjE4OCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzE4LjE4OCAzMTguMTg4OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PHBvbHlnb24gc3R5bGU9ImZpbGw6I0U4RThFODsiIHBvaW50cz0iMjI3LjMyMSw3LjUgNDAuMzQyLDcuNSA0MC4zNDIsMzEwLjY4OCAyNzcuODQ2LDMxMC42ODggMjc3Ljg0Niw1OC4wMjUgIi8+PGc+PGc+PHBhdGggc3R5bGU9ImZpbGw6IzMzM0U0ODsiIGQ9Ik0xMzMuODUyLDEyNC4wODJoLTguODgxVjk5Ljc3NWwwLjA4Ny0zLjk5NGwwLjE0NS00LjM2OGMtMS40NzYsMS40NzYtMi40OTksMi40NDItMy4wNzQsMi45MDFsLTQuODI4LDMuODhsLTQuMjc5LTUuMzQ2bDEzLjUzMi0xMC43NzNoNy4yOTlMMTMzLjg1MiwxMjQuMDgyTDEzMy44NTIsMTI0LjA4MnoiLz48cGF0aCBzdHlsZT0iZmlsbDojMzMzRTQ4OyIgZD0iTTE3NC42MjIsMTAzLjA3OGMwLDcuMzM2LTEuMjAzLDEyLjc2OC0zLjYwNywxNi4yOTNjLTIuNDA0LDMuNTI0LTYuMTA1LDUuMjg1LTExLjEwNCw1LjI4NWMtNC44NDcsMC04LjUtMS44Mi0xMC45NjItNS40NTljLTIuNDYxLTMuNjM5LTMuNjkyLTkuMDEzLTMuNjkyLTE2LjExOWMwLTcuNDEzLDEuMTk3LTEyLjg3NiwzLjU5Mi0xNi4zOTJjMi4zOTQtMy41MTQsNi4wODItNS4yNzEsMTEuMDYzLTUuMjcxYzQuODQ2LDAsOC41MDksMS44NCwxMC45ODgsNS41MTdDMTczLjM4LDkwLjYwOSwxNzQuNjIyLDk1Ljk5LDE3NC42MjIsMTAzLjA3OHogTTE1NC4wNzcsMTAzLjA3OGMwLDUuMTUzLDAuNDQ2LDguODQ1LDEuMzM2LDExLjA3NmMwLjg5MSwyLjIzMiwyLjM5LDMuMzQ4LDQuNDk4LDMuMzQ4YzIuMDY4LDAsMy41NjMtMS4xMzEsNC40ODEtMy4zOTFjMC45Mi0yLjI2LDEuMzc5LTUuOTM3LDEuMzc5LTExLjAzM2MwLTUuMTUtMC40NjUtOC44NTItMS4zOTMtMTEuMTA0Yy0wLjkzLTIuMjQ5LTIuNDE5LTMuMzc2LTQuNDY4LTMuMzc2Yy0yLjA4OCwwLTMuNTgyLDEuMTI3LTQuNDgzLDMuMzc2QzE1NC41MjgsOTQuMjI3LDE1NC4wNzcsOTcuOTI4LDE1NC4wNzcsMTAzLjA3OHoiLz48cGF0aCBzdHlsZT0iZmlsbDojMzMzRTQ4OyIgZD0iTTIwMS4wMjYsMTI0LjA4MmgtOC44NzlWOTkuNzc1bDAuMDg3LTMuOTk0bDAuMTQ0LTQuMzY4Yy0xLjQ3NSwxLjQ3Ni0yLjUsMi40NDItMy4wNzQsMi45MDFsLTQuODI2LDMuODhsLTQuMjgyLTUuMzQ2bDEzLjUzMi0xMC43NzNoNy4yOTlMMjAxLjAyNiwxMjQuMDgyTDIwMS4wMjYsMTI0LjA4MnoiLz48L2c+PGc+PHBhdGggc3R5bGU9ImZpbGw6IzMzM0U0ODsiIGQ9Ik0xNDEuMDMzLDE2MC42NjZjMCw3LjMzNi0xLjIwMSwxMi43NjctMy42MDQsMTYuMjljLTIuNDA1LDMuNTI1LTYuMTA2LDUuMjg3LTExLjEwNSw1LjI4N2MtNC44NDcsMC04LjUwMS0xLjgxOS0xMC45NjMtNS40NThjLTIuNDYxLTMuNjM5LTMuNjkxLTkuMDEyLTMuNjkxLTE2LjExOWMwLTcuNDE0LDEuMTk3LTEyLjg3OCwzLjU5Mi0xNi4zOTNzNi4wODItNS4yNzEsMTEuMDYzLTUuMjcxYzQuODQ2LDAsOC41MDksMS44MzcsMTAuOTg4LDUuNTE2QzEzOS43OTMsMTQ4LjE5NiwxNDEuMDMzLDE1My41NzksMTQxLjAzMywxNjAuNjY2eiBNMTIwLjQ4OSwxNjAuNjY2YzAsNS4xNTIsMC40NDYsOC44NDUsMS4zMzcsMTEuMDc2czIuMzksMy4zNDYsNC40OTcsMy4zNDZjMi4wNjgsMCwzLjU2Mi0xLjEyOSw0LjQ4MS0zLjM4OWMwLjkxOS0yLjI2MSwxLjM3OC01LjkzOCwxLjM3OC0xMS4wMzNjMC01LjE1Mi0wLjQ2My04Ljg1NC0xLjM5My0xMS4xMDVjLTAuOTI5LTIuMjUtMi40MTgtMy4zNzUtNC40NjctMy4zNzVjLTIuMDg4LDAtMy41ODMsMS4xMjUtNC40ODMsMy4zNzVDMTIwLjkzOCwxNTEuODEyLDEyMC40ODksMTU1LjUxNCwxMjAuNDg5LDE2MC42NjZ6Ii8+PHBhdGggc3R5bGU9ImZpbGw6IzMzM0U0ODsiIGQ9Ik0xNjcuNDM4LDE4MS42NjdoLTguODc5di0yNC4zMDZsMC4wODctMy45OTRsMC4xNDQtNC4zNjZjLTEuNDc3LDEuNDc0LTIuNSwyLjQ0Mi0zLjA3MywyLjkwM2wtNC44MjksMy44NzdsLTQuMjc5LTUuMzQ0bDEzLjUzMi0xMC43NzRoNy4yOTh2NDIuMDA0SDE2Ny40Mzh6Ii8+PHBhdGggc3R5bGU9ImZpbGw6IzMzM0U0ODsiIGQ9Ik0yMDEuMDI2LDE4MS42NjdoLTguODc5di0yNC4zMDZsMC4wODctMy45OTRsMC4xNDQtNC4zNjZjLTEuNDc1LDEuNDc0LTIuNSwyLjQ0Mi0zLjA3NCwyLjkwM2wtNC44MjYsMy44NzdsLTQuMjgyLTUuMzQ0bDEzLjUzMi0xMC43NzRoNy4yOTlMMjAxLjAyNiwxODEuNjY3TDIwMS4wMjYsMTgxLjY2N3oiLz48L2c+PC9nPjxwb2x5Z29uIHN0eWxlPSJmaWxsOiMzMzNFNDg7IiBwb2ludHM9IjIzNS4xNCwzMi43NjMgNDAuMzQyLDMyLjc2MyA0MC4zNDIsNy41IDIyNy4zMjEsNy41ICIvPjxwb2x5Z29uIHN0eWxlPSJmaWxsOiNEMUQzRDM7IiBwb2ludHM9IjIyNy4zMjEsNTguMDI1IDI3Ny44NDYsNTguMDI1IDIyNy4zMjEsNy41ICIvPjxwYXRoIHN0eWxlPSJmaWxsOiMzMzNFNDg7IiBkPSJNMTQ2LjQ1MSwyODEuMzcxaDEzLjAyOHYtNDcuOTc5aC0xMy4wMjhWMjgxLjM3MXogTTI4My4xNDksNTIuNzIzTDIzMi42MjUsMi4xOTdDMjMxLjIxOCwwLjc5LDIyOS4zMTEsMCwyMjcuMzIxLDBINDAuMzQyYy00LjE0MywwLTcuNSwzLjM1OC03LjUsNy41djMwMy4xODhjMCw0LjE0MywzLjM1Nyw3LjUsNy41LDcuNWgyMzcuNTA0YzQuMTQzLDAsNy41LTMuMzU3LDcuNS03LjVWNTguMDI1QzI4NS4zNDYsNTYuMDM2LDI4NC41NTYsNTQuMTI5LDI4My4xNDksNTIuNzIzeiBNMjM0LjgyMSwyNS42MDZsMjQuOTE4LDI0LjkxOWgtMjQuOTE4TDIzNC44MjEsMjUuNjA2TDIzNC44MjEsMjUuNjA2eiBNNDcuODQyLDE1aDE3MS45Nzl2MTAuMjYzSDQ3Ljg0MlYxNXogTTI3MC4zNDYsMzAzLjE4OEg0Ny44NDJWNDAuMjYzaDE3MS45Nzl2MTcuNzYzYzAsNC4xNDMsMy4zNTcsNy41LDcuNSw3LjVoNDMuMDI0djIzNy42NjJIMjcwLjM0NnogTTIwMy45OCwyNTUuMDg0YzAsMi45MzIsMC4xNTIsNi44MTUsMC40NTksMTEuNjVoLTAuMTk2bC0xNy40NTktMzMuMzQzSDE2OS44NXY0Ny45NzloMTEuNDg1di0yMS42MjdjMC0yLjgwMS0wLjIwOC02Ljg1OS0wLjYyMy0xMi4xNzZoMC4yOTVsMTcuNTI1LDMzLjgwM2gxNi45OTl2LTQ3Ljk3OUgyMDMuOThWMjU1LjA4NHogTTEyOS42NDksMjU2LjIzMnYtMC4zMjhjMi4yNTItMC41NDcsNC4wNjgtMS43NzEsNS40NDctMy42NzZjMS4zNzktMS45MDIsMi4wNjctNC4yLDIuMDY3LTYuODkxYzAtNC4wOTItMS41ODEtNy4xMDUtNC43NDItOS4wNDFjLTMuMTYxLTEuOTM4LTguMDAzLTIuOTA1LTE0LjUyMS0yLjkwNWgtMTYuNzM3djQ3Ljk3OWgxOC42NzRjNS42NDUsMCwxMC4xMDItMS4yNTIsMTMuMzczLTMuNzU4YzMuMjctMi41MDUsNC45MDYtNS45MzQsNC45MDYtMTAuMjg4YzAtMi45MzItMC42NjgtNS4zMTYtMi4wMDItNy4xNTRDMTM0Ljc3OSwyNTguMzMzLDEzMi42MjQsMjU3LjAyMSwxMjkuNjQ5LDI1Ni4yMzJ6IE0xMTQuMTI2LDI0My4yMDRoMy42NDNjNC4wNywwLDYuMTA0LDEuMzQ2LDYuMTA0LDQuMDM2YzAsMS41MS0wLjUwOSwyLjY0OC0xLjUyNiwzLjQxNHMtMi40NTYsMS4xNDgtNC4zMTUsMS4xNDhoLTMuOTA1TDExNC4xMjYsMjQzLjIwNEwxMTQuMTI2LDI0My4yMDR6IE0xMTguNjg4LDI3MS4zNjFoLTQuNTYzdi0xMC4wNzRoNC4yOTljMi4wNTcsMCwzLjYyNywwLjQyNiw0LjcxLDEuMjc5czEuNjI0LDIuMDc5LDEuNjI0LDMuNjc2QzEyNC43NTksMjY5LjY1NSwxMjIuNzM1LDI3MS4zNjEsMTE4LjY4OCwyNzEuMzYxeiIvPjwvZz48L3N2Zz4=);
            --icon-size: 24px;
            --font-stack: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
            --font-stack-monospace: "Fira Code", Consolas, "Consolas", "Lucida Console", Monaco, monospace;
            --font-size: 80%;
            --default-padding: 1.375rem;
            --container-max-width: none;
            --header-bg: var(--color-main);
            --body-bg: rgba(113, 134, 155, 0.53);
            --panel-shadow: none;
            --panel-padding: 0;
            background-image: url(data:image/png;base64,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);
        }

        @media (min-width: 769px) {
            :root {
                --font-size: 90%;
                --icon-size: 36px;
                --panel-shadow: 0 8px 17px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                --container-max-width: 90%;
            }
        }

        @media (min-width: 992px) {
            :root {
                --font-size: 100%;
                --container-max-width: 960px;
            }
        }

        * { box-sizing: border-box; }

        html { font-size: var(--font-size); }

        body {
            background: var(--body-bg);
            font-size: 1rem;
            line-height: 1.375;
            font-family: var(--font-stack);
            padding: 0;
            margin: 0;
        }

        pre {
            width: 100%;
            overflow: scroll;
        }

        a { color: var(--color-link); }

        a:hover { color: var(--color-link-hover); }

        h1 {
            margin: 0;
            font-weight: 500;
        }

        h2 {
            margin: 0;
            font-weight: 400;
        }

        h1 small,
        h2 small {
            font-family: var(--font-stack-monospace);
            font-size: 0.6em;
            opacity: 0.8;
            font-weight: lighter;
        }

        h1 > span,
        h2 > span {
            font-family: var(--font-stack-monospace);
        }

        .container {
            max-width: var(--container-max-width);
            margin: auto;
        }

        .header {
            background: #333e48;
            background: var(--header-bg);
            color: #ffffff;
            padding: var(--default-padding);
            padding-top: calc(var(--default-padding) - 0.375rem);
            line-height: 1;
        }

        .panel {
            background: #ffffff;
            box-shadow: var(--panel-shadow);
            padding: var(--panel-padding);
        }

        .panel__footer {
            background: #f7f7f7;
            padding: 0.5rem var(--default-padding);
            border-top: 2px solid rgba(0, 0, 0, 0.2);
            color: #999999;
            font-size: 0.8rem;
            font-style: italic;
            display: flex;
            flex-wrap: wrap;
        }

        .panel__footer > * { margin: 0 5px; }

        .panel__footer > *:first-child { margin-left: 0; }

        .icon {
            background: transparent var(--icon-txt); background-size: contain;
            width: var(--icon-size);
            height: var(--icon-size);
            display: block;
            overflow: hidden;
            text-indent: -9999px;
        }

        .icon-folder { background-image: var(--icon-folder); }

        .icon-html { background-image: var(--icon-html); }

        .icon-ico { background-image: var(--icon-ico); }

        .icon-txt { background-image: var(--icon-txt); }

        .icon-bin { background-image: var(--icon-bin); }

        .files {
            margin: 0;
            border-radius: 2px;
            overflow: hidden;
            position: relative;
            line-height: normal;
            width: 100%;
            list-style: none;
            padding: 0;
        }

        .files__group + .files__group {
            border-top: 2px solid rgba(0, 0, 0, 0.2);
        }

        .files__item {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: inherit;
        }

        .files__item:hover { color: inherit; }

        .files__item:nth-child(even) { background: #efefef; }

        .files__icon { margin-right: 0.5rem; }

        .files__title { font-family: var(--font-stack-monospace); white-space: nowrap; }

        .files__desc { display: none; }

        a.files__item .files__title { color: var(--color-link); text-decoration: underline; }

        a.files__item:hover .files__title { color: var(--color-link-hover); }

        @media (max-width: 768px) {
            .files__item { flex-wrap: wrap; align-items: flex-start; }

            .files__desc { display: block; width: calc(100% - 30px); margin-left: 30px; margin-top: -0.7em; }
        }

        @media (min-width: 769px) {
            .files { display: table; }

            .files__group { display: table-row-group; border: none; }

            .files__group + .files__group > .files__item:first-child > .files__cell {
                border-top: 2px solid rgba(0, 0, 0, 0.2);
            }

            .files__item {
                display: table-row;
                padding: 0;
            }

            .files__cell {
                display: table-cell;
                vertical-align: middle;
                padding: 0.5rem 1rem;
            }

            .files__icon {
                margin: 0;
                padding-right: 0;
                width: var(--icon-size);
            }

            .files__title { width: 25%; }
        }

        .page {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .page__main { flex-grow: 1; }

        .footer {
            background: var(--header-bg);
            color: #cccccc;
            text-align: center;
            font-size: 0.85rem;
            padding: 0.5rem var(--default-padding);
        }

        .footer a { color: #eeeeee; text-decoration: underline; }

        .footer a:hover { color: #ffffff; }

        .panel { position: relative; }

        .btn {
            display: inline-block;
            margin: 20px;
            background: linear-gradient(to bottom, #eeeeee, #cccccc);
            border-radius: 4px;
            padding: 0.5rem 2rem;
            text-decoration: none;
            font-weight: 700;
            color: #000000;
            border: 1px solid #bbbbbb; border-top-color: #cccccc;
            position: absolute;
            right: 0;
            top: -75px;
        }

        .markdown {
            /*line-height: 1.2;*/
        }

        .markdown h1 { margin: 0.5em 0; font-size: 1.75em; }

        .markdown h1:first-child { margin-top: 0; }

        .markdown h1::after {
            content: '';
            display: block;
            border-top: 1px solid #cccccc;
        }

        .markdown h2 { margin: 0.5em 0; }

        .markdown ul, .markdown ol {
            line-height: 1.4;
        }

        .markdown b, strong { font-weight: bold; }

        .markdown i, em { font-style: italic; }

        .markdown pre {
            background: #f5f5f5;
            border: 1px solid #cccccc;
            border-radius: 2px;
            overflow-x: auto;
            padding: 5px 10px;
            word-wrap: normal;
            line-height: 1.4;
        }

        .markdown table {
            border-collapse: collapse;
            margin-top: 10px;
            width: 100%;
            font-size: 0.9em;
        }

        .markdown thead, .markdown tfoot, .markdown tbody {
            border-bottom: 2px solid #dfe1e6;
        }

        .markdown tr {
            background: #ffffff;
            border-bottom: 1px solid #dddddd;
            color: #333333;
        }

        .markdown td, .markdown th { padding: 7px 10px; border-top: 1px solid #dddddd; }

        .markdown td:first-child, .markdown th:first-child { padding-left: 0; }

        .markdown code {
            padding: 1px 3px;
            border: 1px solid #eeeeee;
            border-radius: 2px;
            background: #f5f5f5;
            box-sizing: border-box;
            display: inline-block;
            max-width: 100%;
            overflow-x: auto;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .markdown pre code {
            border: none;
            padding: 0;
            white-space: pre;
        }

        .markdown table td > code { border: none; }

        .markdown img { max-width: 100%; }

        .markdown { }
    </style>
</head>
<body>

<div class="page">
    <div class="page__header header">
        <div class="container">
            <h1><?= $_SERVER['HTTP_HOST'] ?></h1>
        </div>
    </div>

    <div class="page__main">
        <div class="container">

            <div class="panel">
                <?php if ($HTML) { ?>
                    <div style="padding: 2rem;">
                        <? if ($DOC_EXISTS) { ?>
                            <a class="btn" href="/" onclick="history.back();return false;">Назад</a>
                        <? } ?>
                        <div class="markdown">
                            <?= $HTML ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <? if ($DOC_EXISTS) { ?>
                        <a class="btn" href="?doc=">Документация</a>
                    <? } ?>
                    <div class="files">
                        <div class="files__group">
                            <? foreach ($dirs as $file) { ?>
                                <? if (basename($file) === DIR_DOCS) { ?>
                                    <a class="files__item" href="?doc=">
                                        <span class="files__cell files__icon"><i class="icon icon-folder"></i></span>
                                        <span class="files__cell files__title"><?= basename($file) ?></span>
                                        <span class="files__cell files__desc"></span>
                                    </a>
                                <? } else { ?>
                                    <div class="files__item">
                                        <span class="files__cell files__icon"><i class="icon icon-folder"></i></span>
                                        <span class="files__cell files__title"><?= basename($file) ?></span>
                                        <span class="files__cell files__desc"></span>
                                    </div>
                                <? } ?>
                            <? } ?>
                        </div>

                        <div class="files__group">
                            <? foreach ($pages as $file) { ?>
                                <a class="files__item" href="<?= basename($file) ?>">
                                    <span class="files__cell files__icon"><i class="icon icon-html"></i></span>
                                    <span class="files__cell files__title"><?= basename($file) ?></span>
                                    <span class="files__cell files__desc"><?= getTitleHtmlPage($file) ?></span>
                                </a>
                            <? } ?>
                        </div>

                        <div class="files__group">
                            <? foreach ($files as $file) { ?>
                                <? if (basename($file) != basename(__FILE__)) { ?>
                                    <a class="files__item" href="<?= basename($file) ?>">
                                        <span class="files__cell files__icon"><i
                                                class="icon icon-<?= ext($file) ?>"></i></span>
                                        <span class="files__cell files__title"><?= basename($file) ?></span>
                                        <span class="files__cell files__desc"></span>
                                    </a>
                                <? } ?>
                            <? } ?>
                        </div>
                    </div>
                    <div class="panel__footer">
                        <div><?= count($pages) ?> страниц<?= getPluralForm(count($pages), ['а', 'ы', '']) ?></div>
                        <div><?= count($files) - 1 ?> файл<?= getPluralForm(count($files) - 1, ['', 'а', 'ов']) ?></div>
                        <div><?= count($dirs) ?> директор<?= getPluralForm(count($dirs), ['ия', 'ии', 'ий']) ?></div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="page__footer">
        <div class="footer">
            <div class="container">
                frontend-tools © 2017 <a href="http://delphinpro.ru" target="_blank">delphinpro</a>
            </div>
        </div>
    </div>
</div>

<!--suppress ALL -->
<script>
    // @formatter:off
  var p={t:function(){window.CSS&&window.CSS.supports&&window.CSS.supports('(--foo: red)')||(p.v={},p.b={},p.o={},p.f(),p.u());},f:function(){var a=document.querySelectorAll('style:not([id*="inserted"]),link[type="text/css"],link[rel="stylesheet"]'),b=1;[].forEach.call(a,function(a){var c;'STYLE'===a.nodeName?(c=a.innerHTML,p.i(a.innerHTML,function(c){a.innerHTML=c,p.s(c,b);})):'LINK'===a.nodeName&&(p.g(a.getAttribute('href'),b,function(a,b){p.i(b.responseText,function(b){p.s(b,a),p.o[a]=b,p.u();});}),c=''),p.o[b]=c,b++;});},s:function(a,b){p.b[b]=a.match(/([^var\/*])--[a-zA-Z0-9\-]+:(\s?)(.+?);/gim);},u:function(){p.r(p.b);for(var a in p.o){var b=p.c(p.o[a],p.v);if(document.querySelector('#inserted'+a))document.querySelector('#inserted'+a).innerHTML=b;else{var c=document.createElement('style');c.innerHTML=b,c.id='inserted'+a,document.getElementsByTagName('head')[0].appendChild(c);}}},c:function(a,b){for(var c in b){var d=new RegExp('var\\(\\s*?'+c.trim()+'(\\)|,([\\s\\,\\w]*\\)))','g');a=a.replace(d,b[c]);}for(var e=/var\(\s*?([^)^,.]*)(?:[\s,])*([^).]*)\)/g;match=e.exec(a);)a=a.replace(match[0],match[2]);return a;},r:function(a){for(var b in a){var c=a[b];c.forEach(function(a){var b=a.split(/:\s*/);p.v[b[0]]=b[1].replace(/;/,'');});}},g:function(a,b,c){var d=new XMLHttpRequest;d.open('GET',a,!0),d.overrideMimeType('text/css;'),d.onload=function(){d.status>=200&&d.status<400?'function'==typeof c&&c(b,d,a):console.warn('an error was returned from:',a);},d.onerror=function(){console.warn('we could not get anything from:',a);},d.send();},i:function(a,b){var d,c=/^(?![\/*])@import\s.*(?:url\(["'])([a-zA-Z0-9.:\/_-]*)["']\)?([^;]*);/gim,e=0,f=0,g={};for(a.search(c)===-1&&b(a);d=c.exec(a);)g[d[1]]=d,e++,p.g(d[1],null,function(c,d,h){a=a.replace(g[h][0],(g[h][2].trim()?'@media '+g[h][2].trim()+' {':'')+d.responseText+(g[h][2].trim()?'}':'')),f++,f===e&&b(a);});}};p.t();
  // @formatter:on
</script>
</body>
</html>