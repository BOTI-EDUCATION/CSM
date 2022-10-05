<template>
  <ul :class="{ tree: isWrapper }">
    <!-- <tree-item v-for="item in list" :key="item.id" :item="item" /> -->
    <li
      v-for="item in list"
      :key="item.id"
      :class="item.children.length > 0 ? 'branch' : ''"
        
    >
      <a href="javascript:void(0)" @click="expand(item)" style="margin-right:10px;" >
      <i :class="{'si':true, 'si-plus': !item.expand , 'si-minus':item.expand}" v-if="item.children.length > 0"></i>
      {{ item.label }}
      </a>
       <router-link :to="'/documentation/add/'+item.id" class="text-success"> <i class="fe fe-plus"></i> </router-link>
       <router-link :to="'/documentation/edit/'+item.id" class="text-primary"> <i class="fe fe-edit-2"></i> </router-link>
      <Tree @item-clicked="expand" :style="{'display': (!item.expand?'none':'inherit')}" :list="item.children" :isWrapper="false" />
    </li>
  </ul>
</template>

<script>
// import TreeItem from './TreeItem';
// import Tree from './Tree';

export default {
  name: "Tree",
  components: {},
  emits: ['item-clicked'],
  props: ["list", "isWrapper"],
  data() {
    return {};
  },
  methods: {
      expand(item){
          console.log(item.children)
          if(item.children.length > 0){
            item.expand = !item.expand;
          }else{
            this.$emit('item-clicked',item);
          }
      }
  },
  mounted() {
  },
  beforeMount(){

  }
};
</script>
<style scoped>
li a:not(:first-of-type){
  display: none;
}
li:hover>a{
  display: initial;
}
</style>