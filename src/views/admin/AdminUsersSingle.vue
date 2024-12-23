<template>
    <div className="wrap-single-news">
        <div className="wrap-news">

            <div class="wrap-field">
                <div class="heading-field">Имя</div>
                <input class='field-admin' v-model="user.name">
            </div>

            <div class="wrap-field">
                <div class="heading-field">Email</div>
                <input class='field-admin' v-model="user.email">
            </div>

            <div class="wrap-field">
              <div class="heading-field">Роль</div>
              <select class="field-admin" v-model="user.role">
                <option value="ROLE_USER">Пользователь</option>
                <option value="ROLE_ADMIN">Админ</option>
              </select>
            </div>

            <div class="wrap-field">
                <div class="heading-field">Дата регистрации</div>
                <span>{{  convertTime(user.created_at?.date) }} </span>
            </div>

            <div class="wrap-save">
                <div class="save news" @click="save()">Сохранить</div>
                <div class="text-status" v-if="saveStatus==='success' || saveStatus==='save_success_redirect'">
                    <span>Успешно сохранено</span>
                </div>
            </div>

            <div class="auth-text form-auth-false"  v-if="error !== ''">
                    <span v-if="Array.isArray(error)">
                          <span v-for="(el, key) in error" :key="key"> {{ el.message }}</span>
                    </span>
            <span v-else> {{ error }}</span>
            </div>

        </div>
    </div>
</template>



<script setup>
import {onMounted, ref} from 'vue';
import router from "@/router/router";
import {useRoute} from "vue-router";
import {authRequest} from "@/api.js";
import {convertTime} from "@/script/convertTime";


let error =  ref('');
const route = useRoute();
let user = ref({
    name : '',
    email : '',
    role : '',

});
let textEditor = ref('');
let saveStatus = ref('');


//get post info
onMounted(
    async () => {
      let response = await authRequest('api/users/' + route.params.id, 'get');

      if ( response.data.status === 'success' ){
        user.value = response.data.json;
        textEditor.value = response.data.json.content;
      }
      else {
        return router.put({ name: '404',  query: { textError: encodeURIComponent(response.data.text) } })
      }
    }
);

//update post
async function save(){

    let data = {
      name: user.value.name,
      email: user.value.email,
      role: user.value.role,
    }

    let response = await authRequest('api/users', 'put', data);

    if (response.data.status === 'success') {
      error.value ='';
    }
    else {
      error.value = response.data.errors;
    }

}


</script>


<style scoped>
.text-status {
    color: #09be92;
    padding: 10px;
    display: inline-block;
    cursor: pointer;
    font-weight:600
}
.wrap-save {
    display:flex;
}
.img-field {
    display:flex;
    align-items: center;
}
.img-field img {
    max-width:100px;
    margin-right:30px;
}
.img-field .field-admin {
    margin-bottom: 0px;
}
.wrap-field {
    margin-bottom: 30px;
}
.field-admin {
    font-size: 13px;
    border-color: #c2c2bf;
    background-color: rgb(249, 249, 249);
    border-radius: 3px;
    border-width: 1px;
    height: 35px;
    padding: 1px 2px 1px 10px;
    outline: none !important;
    transition: 0.3s;
    width: 100%;
    vertical-align: middle;
    box-shadow: 0 0px 0px rgba(0, 0, 0, 0.075) inset !important;
    border-style: solid;
    margin: 0px;
    box-sizing: border-box;
    max-width: 100%;
}
input:focus, textarea:focus {
    border-color: #4e41d9;
}

.heading-field {
    margin-bottom: 5px;
    font-weight: 600;
}

.save {
    background-color: #09be92;
    color:#fff;
    padding:10px;
    display: inline-block;
    cursor: pointer;
}
.save:hover {
    background-color: #099d79;
}

.textarea-field {
    min-height: 80px;
    padding-top: 10px;
}



</style>
