<template>
    <div class="wrap-news">
        <div class="news-list">
            <div class="post-el" v-for="(post) in arrayPostEl" :key="post.id">
                <div class="heading-post">{{ post.name }}</div>
                <div class="wrap-section-post">
                    <div class="thumb-post"><img :src="post.img" alt="new-thumb"></div>
                    <div class="post-short-text">
                        <div class="content-short-post">{{ post.short_description }}</div>
                    </div>
                </div>
                <a :href="'/posts/'+post.id">Читать далее</a>
            </div>

        </div>

        <div class="pagination-post" v-if="props.pagination === 'true' ">
            <div class="pagination-el" v-for="(pagination) in arrayPagination" :key="pagination">
                <a :href="pagination.url">{{ pagination.label }}</a>
            </div>
        </div>

        <div class="empty-list" v-if="emptyPage">По вашему запросу не найдено новостей</div>

    </div>
</template>




<script setup>
import {onMounted, ref} from 'vue';
import {useRoute} from "vue-router";
import { notAuthRequest} from "@/api.js";
import { defineProps} from 'vue'
let props = defineProps({
    total: String,
    page: String,
    pagination : String,
});

const route = useRoute();

let arrayPostEl = ref([]);
let arrayPagination = ref([]);
let emptyPage = ref(null);

onMounted(
    async () => {

        let page = route.params.page;
        if (typeof props.page !=='undefined'){
            page = props.page;
        }

        let response = await notAuthRequest('api/posts?page='+page, 'get' );

        if ( response.data.status === 'success' ){

          let arrayPost = response.data.json;
            //total post for page
            if (typeof props.total !=='undefined'){
                if ( arrayPost.length >props.total ){
                    arrayPost.splice(props.total);
                }
            }

            //short description post
            for (let i = 0; i < arrayPost.length; i++) {
                if (arrayPost[i]['short_description'].length > 30) {
                    arrayPost[i]['short_description'] = arrayPost[i]['short_description'].slice(0, 80) + '...';
                }
            }
          arrayPostEl.value = arrayPost


        }
        else {
            emptyPage.value = true;
        }

    }
);

</script>


<style scoped>
    .post-el {
        display: flex;
        flex-direction: column;
        width: 50%;
        margin-bottom: 30px;
    }

    .thumb-post img {
        max-width: 100px;
    }

    .news-list {
        display: flex;
        flex-wrap: wrap;
        margin-top: 30px;
    }

    .wrap-section-post {
        display: flex;
    }

    .post-el:nth-child(odd) {
        padding-right: 30px;
    }

    .post-el:nth-child( even) {
        padding-left: 30px;
    }

    .post-short-text {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .content-short-post {
        max-width: 400px;
        margin: 10px 0;
        padding-left: 20px;
    }

    .heading-post {
        font-size: 20px;
    }

    .pagination-post {
        display: flex;
    }

    .pagination-el {
        margin-left: 10px;
        margin-right: 10px;
    }

    .pagination-el:first-child {
        margin-left: 0px;
    }


</style>
