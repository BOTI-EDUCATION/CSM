<template>
    <div class="container">
        <div v-if="eventItem" class="row">
            <div class="col-12" v-if="eventItem.title">
                {{eventItem.title}}
            </div>
            <div class="col-12 p-0" v-if="eventItem.extendedProps.type">
                <span class="tag tag-sm tag-rounded">{{eventItem.extendedProps.type.label}}</span>
            </div>
            <div class="col-12" v-if="eventItem.extendedProps.user&&eventItem.extendedProps.user.img">
                <img :src="eventItem.extendedProps.user.img" class="rounded" alt="">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props:{
        eventItem : null,
        eventJson:''
    },
    methods: {
    },
    mounted(){
        console.log(this.eventItem)
    }
}
</script>