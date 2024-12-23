<template>
  <div class="wrap-news">
    <div class="news-list">
      <a class="add-new" href="/admin/posts/add">Добавить новость</a>

      <div class="post-heading-block">
        <div class="post-name">Название новости</div>
        <div class="post-author">Автор</div>
        <div class="wrap-date">
          <div class="post-date-c"><span>Дата создания и обновления:</span> </div>
        </div>
      </div>

      <div class="post-el" v-for="(post) in arrayPosts" :key="post.id">
        <a :href="'/admin/posts/'+post.id" class="post-name">{{ post.name }}</a>
        <div class="post-author">{{ post.author }}</div>
        <div class="wrap-date">
          <div class="post-date-c"><span>Дата создания:</span> <span>{{ convertTime(post.created_at.date) }}</span></div>
        </div>
        <div class="remove-post" @click="removePost" :data-id="post.id">Удалить</div>
      </div>


      <pagination v-model="pageModel" :records="pageTotal" :per-page="1" @paginate="paginationListing"/>

    </div>
  </div>
</template>


<script setup>

import {ref} from 'vue';
import {authRequest} from "@/api.js";
import Pagination from "v-pagination-3";
import {convertTime} from "@/script/convertTime";
let pageModel = ref(1)
let pageTotal = ref(1)
let arrayPosts = ref([]);

async function paginationListing() {
  let response = await authRequest('api/posts?page=' + pageModel.value, 'get');

  if (response.data.status === 'success') {
    arrayPosts.value = response.data.json.data;
    pageTotal.value = response.data.json.last_page;
  }
  else{
    arrayPosts.value = []
  }
}
paginationListing();


async function removePost(e){
  let id = e.target.getAttribute('data-id');

  let response = await authRequest('/api/posts/'+id, 'delete');

  if ( response.data.status==='success' ){
    e.target.closest('.post-el').remove();
  }
  else {
    console.error(response.data.status);
  }
}
</script>


<style scoped>

.post-el {
  display: flex;
  width: 100%;
  border: 1px solid #c1c0c0;
  align-items: center;
  background-color: #fcfcfc;
  margin-top: -1px;
}

.post-el:nth-child(odd) {
  background-color: #ededed;
}

.post-el:hover {
  background-color: #d4d4d4;
}

.thumb-post img {
  max-width: 100px;
}

.post-name {
  flex-basis: 40%;
  padding-left: 20px;
  color: #367c75;
  text-decoration: none;
  transition: 0.3s;
  font-size: 16px;
  font-weight: 600;
}

.post-author {
  flex-basis: 20%;
}

.pagination-post {
  display: flex;
  margin-top:20px;
}

.pagination-el {
  margin-left: 10px;
  margin-right: 10px;
}
.pagination-el:first-child{
  margin-left: 0px;
}

.post-heading-block {
  display: flex;
  width: 100%;
  border: 1px solid #c1c0c0;
  align-items: center;
  background-color: #383838;
  color: #fff;
  margin-top: 30px;
}

.post-heading-block div {
  padding-top: 5px;
  padding-bottom: 5px;
}

.post-heading-block .post-name {
  color: #fff;
  font-weight:400;
}

.wrap-date {flex-basis: 25%}

.remove-post {
  padding-left: 20px;
  text-align: right;
  font-size: 12px;
  color: #b50707;
  cursor: pointer;
}

.pagination-el div {
  cursor: pointer;
}

.add-new {
  background-color: #13af3b;
  display: inline-block;
  padding: 10px;
  color: #fff;
  text-decoration: none;
  transition: 0.3s;
  margin-bottom: 10px;
}

.news-list {
  margin-top:20px
}


</style>
