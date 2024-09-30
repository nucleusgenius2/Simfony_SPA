<template>
    <Header v-if="props.layout === 'mainLayout'" :auth="props.auth" />
    <Menu v-if="props.layout === 'mainLayout'" />

    <div class="content">
        <slot name="content"></slot>
    </div>

    <Footer v-if="props.layout === 'mainLayout'" />
</template>


<script setup>
import Menu from '@/components/MainMenu.vue';
import Footer from '@/layout/FooterSection.vue';
import Header from '@/layout/HeaderSection.vue';
import { defineProps} from 'vue'
let props = defineProps({
    layout: String,
    auth: String
});


</script>

<style scoped>

</style>
