<template>
    <li :class="(item.children.length > 0?'branch':'')">
        <i class="si si-plus" v-if="item.children.length > 0"></i>
        {{item.label}}
        <!-- <Tree :list="item.children" :isWrapper="false" /> -->
    </li>
</template>

<script>
import Tree from './Tree';

export default {
    name: treeItem
    components:{
        Tree
    },
    props: ['item'],
    data() {
        return {
            
        }
    },
    methods: {
        
    },
    mounted() {
        this.item.expand = false;
    },
}

</script>
