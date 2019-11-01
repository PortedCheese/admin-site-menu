<template>
    <div class="list-group position-relative">
        <draggable :list="changeable" group="structure" handle=".main-handle" @change="checkMove">
            <menu-item v-for="item in changeable"
                       :item="item"
                       v-on:changed="checkMove"
                       handle="main-handle"
                       v-bind:key="item.id">
            </menu-item>
        </draggable>

        <button class="btn btn-success position-fixed fixed-bottom mx-auto mb-3"
                v-if="weightChanged"
                @click="changeOrder"
                :disabled="loading"
                :class="weightChanged ? 'animated bounceIn' : ''">
            Сохранить порядок
        </button>
    </div>
</template>

<script>
    import MenuItem from "./MenuItemComponent";
    import draggable from 'vuedraggable';

    export default {
        components: {
            'menu-item':MenuItem,
            draggable,
        },

        props: {
            structure: {
                type: Array,
                required: true,
            },
            updateUrl: {
                type: String,
                required: true,
            }
        },

        created() {
            this.changeable = this.structure;
        },

        data() {
            return {
                changeable: [],
                weightChanged: false,
                loading: false,
            }
        },

        methods: {
            checkMove() {
                this.weightChanged = true;
            },

            changeOrder() {
                this.loading = true;
                axios
                    .put(this.updateUrl, {
                        items: this.changeable
                    })
                    .then(response => {
                        let result = response.data;
                        this.weightChanged = false;
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            title: result,
                            showConfirmButton: false,
                            timer: 2500
                        })
                    })
                    .catch(error => {
                        let data = error.response.data;
                        Swal.fire({
                            position: 'top-end',
                            type: 'error',
                            title: data,
                            showConfirmButton: false,
                            timer: 2000
                        })
                    })
                    .finally(() => {
                        this.loading = false;
                    })
            }
        }
    }
</script>

<style scoped>

</style>