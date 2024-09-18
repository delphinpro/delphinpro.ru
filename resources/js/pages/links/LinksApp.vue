<!--
  - Site delphinpro.ru
  - Copyright (c) 2024.
  -->

<script setup>

import axios from 'axios';
import { computed, onMounted, ref } from 'vue';

//== Categories
//== ======================================= ==//

const categories = ref([]);
const catsPrevLink = ref('');
const catsNextLink = ref('');
const catsCurrentPage = ref(1);
const catsLastPage = ref(1);

const selectedCats = ref([]);

async function loadCategories(url) {
    const res = await axios.get(url);
    console.log({ res });
    categories.value = res.data;
    catsPrevLink.value = res['prev_page_url'];
    catsNextLink.value = res['next_page_url'];
    catsCurrentPage.value = res['current_page'];
    catsLastPage.value = res['last_page'];
}

onMounted(() => {
    loadCategories('/links/categories');
});

const catsPrevPage = () => {
    let prev = Math.max(catsCurrentPage.value - 1, 1);
    loadCategories('/links/categories' + '?page=' + prev);
};

const catsNextPage = () => {
    let next = Math.min(catsCurrentPage.value + 1, catsLastPage.value);
    loadCategories('/links/categories' + '?page=' + next);
};

let timeout = null;
const filterCategories = (e) => {
    clearTimeout(timeout);
    timeout = setTimeout(async () => {
        await loadCategories('/links/categories' + '?search=' + e.target.value);
    }, 250);
};

//== Links
//== ======================================= ==//

const links = ref([]);

async function loadLinks(url) {
    const res = await axios.get(url);
    console.log({ res });
    links.value = res.data;
}

onMounted(() => {
    loadLinks('/links/links');
});

let timeout2 = null;
const filterLinksByName = (e) => {
    clearTimeout(timeout2);
    timeout2 = setTimeout(async () => {
        await loadLinks('/links/links' + '?search=' + e.target.value);
    }, 250);
};

const filteredLinks = computed(() => {
    if (!selectedCats.value.length) {
        return links.value;
    }

    return links.value.filter(link => link.categories.map(c => c.id).some(id => selectedCats.value.includes(id)));
});

</script>

<template>
    <div class="row">
        <div class="col-md-4 col-xl-3">
            <div class="panel p-3">
                <h3>Категории</h3>
                <div v-if="catsLastPage > 1" class="form-group">
                    <input class="form-control" placeholder="Поиск категорий..." type="text" @input="filterCategories">
                </div>
                <div class="mt-3">
                    <ul class="list-unstyled">
                        <li v-for="category in categories" :key="category.id">
                            <div class="form-check">
                                <input :id="'c'+category.id"
                                    v-model="selectedCats"
                                    :value="category.id"
                                    class="form-check-input"
                                    type="checkbox"
                                >
                                <label :for="'c'+category.id" class="form-check-label">
                                    {{ category.title }}
                                </label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div v-if="catsLastPage > 1" class="mt-3 d-flex align-items-center justify-content-between">
                    <a v-if="catsPrevLink" :href="catsPrevLink" @click.prevent="catsPrevPage">Назад</a>
                    <span v-else>Назад</span>
                    {{ catsCurrentPage }}
                    <a v-if="catsNextLink" :href="catsNextLink" @click.prevent="catsNextPage">Далее</a>
                    <span v-else>Далее</span>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-xl-9 mt-4 mt-md-0">
            <div class="panel p-3">
                <h3>Полезные ссылки</h3>
                <div class="input-group input-group-sm">
                    <!--<span class="input-group-text">Поиск ссылок</span>
                    <input class="form-control col-3" placeholder="По категории" type="text">-->
                    <input class="form-control" placeholder="Поиск ссылок..." type="text" @input="filterLinksByName">
                </div>
            </div>
            <div class="mt-4 links">
                <a v-for="link in filteredLinks" :key="link.id" :href="link.url" class="link-box" target="_blank">
                    <div v-if="link.cover"
                        :class="{'link-box__cover--backdrop': !!link.background}"
                        :style="{background : link.background}"
                        class="link-box__cover"
                    >
                        <img :src="link.cover" alt="" class="link-box__image">
                    </div>
                    <div class="link-box__main">
                        <div class="link-box__title">
                            <span>{{ link.title }}</span>
                            <i v-if="link.description">?</i>
                        </div>
                        <div class="link-box__cats">
                            {{ link.categories.map((c) => c.title).join(', ') }}
                        </div>
                        <div v-if="link.description" class="link-box__desc">
                            {{ link.description }}
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</template>
