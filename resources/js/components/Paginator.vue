<template>
    <div v-if="paginator.has_pages">
        <nav aria-label="...">
            <ul class="pagination">
                <li v-for="item in paginator.path_list" v-bind:key="item.key" class="page-item" v-bind:class="{ active: item.active }">
                    <a v-if="item.url" class="page-link" @click.prevent="setPage('page', item.page)">{{item.label}}</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    props: {
        paginator: {}
    },
    methods:{
        setPage(key, val){
            this.$emit('setPage', key, val)
        }
    }
}
</script>
