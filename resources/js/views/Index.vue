<template>
    <div class="container">
        {{ token }}
    </div>
</template>

<script>
    export default {
        name: 'Index',
        computed: {
            token () {
                return this.$store.state.token
            }
        }
    }
</script>
