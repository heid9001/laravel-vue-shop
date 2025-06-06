<template>
<div>
    <div>
        <input type="text" :value="foo" @input="onInput">
    </div>
</div>
</template>

<script>
export default {
    props: {
        foo: "empty"
    },
    methods: {
        onInput(event) {
            console.log(event.target.value);
            this.$emit('foo-changed', `from-child: ${event.target.value}`);
        }
    }
}
</script>

<style>

</style>
