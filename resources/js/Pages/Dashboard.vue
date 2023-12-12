<script setup>
import { onMounted, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/inertia-vue3';
import { Head, Link } from '@inertiajs/vue3';
import Pagination from '@/components/Pagination.vue';

defineProps({
    products: Object
})

</script>


<template>
    <Head title="Products" />
  
    <AuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Your Products</h2>
      </template>

      <section v-if="!loading" id="Projects" class="w-full max-w-screen-lg mx-auto grid grid-cols-1 lg:grid-cols-3 md:grid-cols-2 justify-items-center gap-y-20 gap-x-14 mt-10 mb-5">
       
        <!-- Use v-for para iterar sobre o array de produtos -->
        <div v-for="product in products.data" v-if='products.data.length' :key="product.id" class="w-72 bg-white shadow-md rounded-xl duration-500 hover:scale-105 hover:shadow-xl">
          <a >
            <img :src="product.imageUrl" :alt="product.name" class="h-80 w-72 object-cover rounded-t-xl" />
            <div class="px-4 py-3 w-72">
              <span class="text-gray-400 mr-3 uppercase text-xs">{{product.sku}}</span>
              <p class="text-lg font-bold text-black truncate block capitalize">{{ product.name }}</p>
              <div class="flex items-center">
                <p class="text-lg font-semibold text-black cursor-auto my-3">${{ product.price }}</p>
                
                
              </div>
            </div>
          </a>
        </div>
        
      </section>
      <Pagination :pagination="products.meta" />
    


              
    </AuthenticatedLayout>
  </template>
  
