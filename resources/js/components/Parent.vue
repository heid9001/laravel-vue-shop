<template>
    <div>
        <p>Вводим в дочернем:</p>
        <Child :foo="parentFoo" @foo-changed="parentFoo = $event"></Child>
        <p>Получаем в родительском: {{ parentFoo }}</p>
    </div>
</template>

<script>
import Child from "./Child.vue";

export default {
    components: {Child},
    data() {
        return {
            parentFoo: "empty"
        }
    }
}
</script>
<style></style>
