<template>
    <div class="row">
        <div class="btn-group btn-group-sm text-info">
            <button class="btn btn-outline-info"
                    @click="downWeight()"
                    :disabled="currentWeight < 1 || loading"
                    type="button">
                <i class="fas fa-minus"></i>
            </button>
            <button class="btn btn-outline-default" disabled="">
                {{ currentWeight}}
            </button>
            <button class="btn btn-outline-info"
                    @click="upWeight()"
                    :disabled="loading"
                    type="button">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['csrfToken', 'weight', 'itemId', 'url'],
        data() {
            return {
                currentWeight: false,
                loading: false
            }
        },
        methods: {
            downWeight () {
                this.currentWeight--;
                this.changeWeight();
            },
            upWeight () {
                this.currentWeight++;
                this.changeWeight();
            },
            // Меняем на введенный вес.
            changeWeight () {
                this.loading = true;
                let formData = new FormData();
                formData.append('changed', this.currentWeight);
                formData.append('weight', this.weight);
                axios.post(this.url, formData, {
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                }).then(response => {
                    this.loading = false;
                    let result = response.data;
                }, response => {
                    this.loading = false;
                })
            },
        },
        created() {
            this.currentWeight = this.weight;
        }
    }
</script>

<style scoped>
    .disabled-weight {
        max-width: 40px;
        text-align: center;
    }
</style>