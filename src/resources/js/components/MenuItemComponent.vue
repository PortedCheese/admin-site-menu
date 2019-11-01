<template>
    <div class="list-group-item list-group-item-action">
        <div class="d-block d-md-flex justify-content-between align-items-center">
            <div>
                <i class="fa fa-align-justify handle cursor-move" :class="handle"></i>
                {{ changeable.title }}
                <span class="badge badge-primary" v-if="changeable.children.length">{{ changeable.children.length }}</span>
                <br>
                <a :href="changeable.url">{{ changeable.url }}</a>
            </div>
            <div>
                <div role="toolbar" class="btn-toolbar mt-3 mt-md-0">
                    <div class="btn-group btn-group-sm mr-1">
                        <a :href="changeable.editItemUrl" class="btn btn-primary">
                            <i class="far fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-danger" :data-confirm="'delete-form-' + changeable.id">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                    <div class="btn-group btn-group-sm" v-if="! changeable.parent_id">
                        <button v-if="changeable.children.length"
                                data-toggle="collapse"
                                :data-target="'#collapse-' + changeable.id"
                                role="button"
                                class="btn btn-secondary">
                            <i class="fas fa-stream"></i>
                        </button>
                        <a :href="changeable.createChildUrl"
                           v-if="! changeable.method"
                           class="btn btn-success">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
                <confirm-form :id="'delete-form-' + changeable.id">
                    <template>
                        <form :action="changeable.deleteItemUrl"
                              :id="'delete-form-' + changeable.id"
                              class="btn-group"
                              method="post">
                            <input type="hidden" name="_token" :value="token">
                            <input type="hidden" name="_method" value="DELETE">
                        </form>
                    </template>
                </confirm-form>
            </div>
        </div>
        <div class="w-100 d-none d-md-block"></div>
        <div class="list-group mt-3 collapse" :id="'collapse-' + changeable.id" v-if="changeable.children.length">
            <draggable :list="changeable.children"
                       :group="'item-group' + changeable.id"
                       @change="checkMove"
                       :handle="'.item-handle-' + changeable.id">
                <menu-item v-for="child in changeable.children"
                           :item="child"
                           v-on:changed="checkMove"
                           :handle="'item-handle-' + changeable.id"
                           :key="child.id">
                </menu-item>
            </draggable>
        </div>
    </div>
</template>

<script>
    import draggable from 'vuedraggable'

    export default {
        name: "menu-item",
        components: {
            draggable,
        },
        props: {
            item: {
                type: Object,
                required: true
            },
            handle: {
                type: String,
                required: true,
            }
        },

        data() {
            return {
                token: false,
                changeable: [],
            }
        },

        created() {
            this.token = axios.defaults.headers.common['X-CSRF-TOKEN'];
            this.changeable = this.item;
        },

        methods: {
            checkMove() {
                this.$emit('changed');
            }
        }
    }
</script>

<style scoped>

</style>