<template>
    <div class="wrap-news">
        <div class="news-list">

            <div class="post-heading-block table-users">
                <div class="post-name">Ник</div>
                <div class="user-email">Email</div>
                <div class="user-status">Статус</div>
                <div class="wrap-date">Дата регистрации</div>
            </div>

            <div>
                <div class="post-el table-users" v-for="(user) in  arrayUsers" :key="user.id">
                <div><a :href="'/admin/users/'+user.id" class="post-name">{{ user.name }}</a></div>
                <div class="user-email">{{ user.email }}</div>
                <div class="user-status">{{ user.status }}</div>
                <div class="wrap-date" >{{ convertTime(user?.created_at?.date) }}</div>
               </div>
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
let arrayUsers = ref([]);

async function paginationListing() {
  let response = await authRequest('api/users?page=' + pageModel.value, 'get');

  if (response.data.status === 'success') {
    arrayUsers.value = response.data.json.data;
    pageTotal.value = response.data.json.last_page;
  }
  else{
    arrayUsers .value = []
  }
}
paginationListing();



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
    padding-left: 20px;
    color: #367c75;
    text-decoration: none;
    transition: 0.3s;
    font-size: 16px;
    font-weight: 600;
}

.table-users > div  {
    flex-basis: 25%;
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


.pagination-el div {
    cursor: pointer;
}


.news-list {
    margin-top:20px
}


</style>
