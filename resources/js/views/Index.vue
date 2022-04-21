<template>
    <div class="container">
        {{ token }}
        {{loltoken}}
    </div>
</template>

<script>
    export default {
        name: 'Index',
        computed: {
            token () {
                return this.$store.state.token
            },
            loltoken () {
                return localStorage.getItem('token')
            }
        }
    }
</script>
