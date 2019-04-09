<template>
    <div class="row">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <button class="btn btn-outline-secondary"
                        @click="downWeight()"
                        :disabled="currentWeight < 1 || loading"
                        type="button">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
            <input class="form-control disabled-weight" type="text" v-model="currentWeight" disabled>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary"
                        @click="upWeight()"
                        :disabled="loading"
                        type="button">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
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
                    if (result.success) {
                        this.weight = result.weight;
                    }
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