<template>
    <div class="container my-5">
        <div class="row justify-content-end mb-4">
            <div class="col-4">
                <button class="btn btn-primary" @click="create">
                    <i class="fas fa-plus-circle mr-1"></i> Create
                </button>
            </div>
            <!-- Search -->
            <div class="col-4">
                <form @submit.prevent="view">
                    <div class="input-group">
                        <input
                            type="text"
                            v-model="search"
                            placeholder="Search"
                            class="form-control"
                        />
                        <div class="input-group-append">
                            <button class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- End Search -->
        </div>
        <!-- Create-->
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <!-- We use [isEditMode] to change the form header name -->
                    <h4 class="card-header">
                        {{ isEditMode ? "Edit" : "Create" }}
                    </h4>
                    <div class="card-body">
                        <alert-error
                            :form="product"
                            :message="message"
                        ></alert-error>
                        <form
                            @submit.prevent="isEditMode ? update() : store()"
                            @keydown="product.onKeydown($event)"
                        >
                            <div class="form-group">
                                <label>Name: </label>
                                <input
                                    v-model="product.name"
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'is-invalid':
                                            product.errors.has('name'),
                                    }"
                                />
                                <!-- FOR VFORM ERROR CHECK -->
                                <HasError :form="product" field="name" />
                                <!-- <div class="text-danger" v-if="product.errors.has('price')" v-html="product.errors.get('price')" /> -->
                            </div>
                            <div class="form-group">
                                <label>Price: </label>
                                <input
                                    v-model="product.price"
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'is-invalid':
                                            product.errors.has('name'),
                                    }"
                                />
                                <HasError :form="product" field="name" />
                                <!-- <div class="text-danger" v-if="product.errors.has('price')" v-html="product.errors.get('price')" /> -->
                            </div>
                            <button
                                class="btn btn-primary"
                                :disabled="product.busy"
                                type="submit"
                            >
                                <i class="fas fa-save mr-1"></i> Save
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Create End -->
            <!-- Table -->
            <div class="col-8">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- For Pagination -->
                        <tr v-for="product in products.data" :key="product.id">
                            <td>{{ product.id }}</td>
                            <td>{{ product.name }}</td>
                            <td>{{ product.price }}</td>
                            <td>
                                <button
                                    class="btn btn-success btn-sm"
                                    @click="edit(product)"
                                >
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                <button
                                    class="btn btn-danger btn-sm"
                                    @click="destroy(product.id)"
                                >
                                    <i class="fas fa-trash-alt mr-1"></i>Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- For Pagination -->
                <pagination
                    :data="products"
                    @pagination-change-page="view"
                ></pagination>
            </div>
            <!-- End Table -->
        </div>
        <!-- For Loading -->
        <loading  class="text-primary" :active.sync="isLoading" :is-full-page="true"></loading>
    </div>
</template>

<script>

import Form from "vform";
import { HasError, AlertError } from "vform/src/components/bootstrap4";

import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';


export default {
    components: {
        HasError,
        AlertError,
        Loading
    },
    name: "ProductComponent",
    data() {
        return {
            isLoading: false,
            isEditMode: false,
            // products : [],
            // for search
            search: "",
            //Change from array to object to use Pagination
            products: {},
            // product : {
            //     id : '',
            //     name : '',
            //     price : ''
            // }

            // For VForm
            product: new Form({
                id: "",
                name: "",
                price: "",
            }),
            message: "",
        };
    },
    methods: {
        // For Search
        // searchProduct(){
        //     axios.get('/api/product?search=' + this.search )
        //     .then(response => this.products = response.data);
        // },
        // End Search

        view(page = 1) {
            // axios.get('/api/product')

            // For Pagination
            // axios.get('/api/product?page=' + page)
            // For ProrgressBar
            this.$Progress.start();
            this.isLoading = true ;
            // Combining write with search and pagination
            axios
                .get(`/api/product?page=${page}&search=${this.search}`)
                .then((response) => {
                    this.products = response.data;
                    this.$Progress.finish()
                    this.isLoading = false

                }).catch(error => {
                    this.$Progress.fail();
                    this.isLoading = false
                })
                ;
        },
        // To create data
        // Send Data with VForm
        store() {
            // axios.post('/api/product',this.product)
            this.product
                .post("/api/product")
                .then((response) => {
                    this.view();
                    this.product.reset();
                    Toast.fire({
                    icon: "success",
                    title: "Created successfully",
            });
                    // To clear input data
                    // this.product.id = '' ;
                    // this.product.name = '' ;
                    // this.product.price = '' ;
                })
                .catch((error) => {
                    this.message = error.response.data.message;
                });
        },
        create() {
            // this.product.onKeydown(KeyboardEvent);
            this.product.clear();
            this.isEditMode = false;
            // use vform method
            this.product.reset();
            // this.product.id = '' ;
            // this.product.name = '' ;
            // this.product.price = '' ;
        },
        edit(product) {
            this.product.clear();
            this.isEditMode = true;
            // Instead of following three sentences,we use fill() ;
            this.product.fill(product);
            // this.product.id = product.id ;
            // this.product.name = product.name ;
            // this.product.price = product.price ;
        },
        update() {
            // Send Data With VForm
            // axios.put(`/api/product/${this.product.id}`,this.product)
            this.product
                .put(`/api/product/${this.product.id}`)
                .then((response) => {
                    this.view();
                    this.product.reset();
                    Toast.fire({
                        icon: "success",
                        title: "Updated successfully",
                    });
                    // this.product.id = '' ;
                    // this.product.name = '' ;
                    // this.product.price = '' ;
                });
        },
        destroy(id) {
            Swal.fire({
                title: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Delete",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/product/${id}`).then((response) => {
                        this.view();
                        Swal.fire({ title: "Deleted!", icon: "success" });
                        // For Toast
                        Toast.fire({
                            icon: "success",
                            title: "Deleted successfully",
                        });
                    });
                }
            });
            // if(!confirm('Are you want to delete')){
            //     return;
            // }
            // axios.delete(`/api/product/${id}`)
            // .then(response => this.view());
        },
    },
    created() {
        this.view();
    },
};
</script>
