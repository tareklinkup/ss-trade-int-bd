<style>
    .v-select {
        margin-bottom: 5px;
    }

    .v-select .dropdown-toggle {
        padding: 0px;
    }

    .v-select input[type=search],
    .v-select input[type=search]:focus {
        margin: 0px;
    }

    .v-select .vs__selected-options {
        overflow: hidden;
        flex-wrap: nowrap;
    }

    .v-select .selected-tag {
        margin: 2px 0px;
        white-space: nowrap;
        position: absolute;
        left: 0px;
    }

    .v-select .vs__actions {
        margin-top: -5px;
    }

    .v-select .dropdown-menu {
        width: auto;
        overflow-y: auto;
    }

    .record-table{
		width: 100%;
		border-collapse: collapse;
	}
	.record-table thead{
		background-color: #0097df;
		color:white;
	}
	.record-table th, .record-table td{
		padding: 3px;
		border: 1px solid #454545;
        text-align: center;
	}
    .record-table th{
        text-align: center;
    }
</style>

<div id="app">
    <div class="row" style="border-bottom: 1px solid #ccc;padding: 3px 0;">
		<div class="col-md-4 col-md-offset-4">
			<form class="form-inline" id="searchForm" @submit.prevent="getSearchResult">
				<div class="row">
					<label class="col-md-2">Product</label>
                    <div class="col-md-9">
                        <v-select v-bind:options="products" v-model="selectedProduct" label="display_text"></v-select>
                    </div>
				</div>
			</form>
		</div>
	</div>

    <div class="row" style="margin-top: 20px;display: none" :style="{display: stock.length > 0 ? '' : 'none'}">
        <div class="col-md-8 col-md-offset-2">
            <div class="table-responsive">
                <table class="record-table">
                    <thead>
                        <tr>
                            <th>Serial</th>
                            <th>Branch</th>
                            <th>Product Id</th>
                            <th>Product Name</th>
                            <th>Current Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in stock">
                            <td>{{ product.sl }}</td>
                            <td>{{ product.Brunch_name }}</td>
                            <td>{{ product.Product_Code }}</td>
                            <td>{{ product.Product_Name }}</td>
                            <td style="text-align: right">{{ product.current_quantity }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align: right;"><strong>Total Quantity</strong></td>
                            <td style="text-align: right;">
                                <strong>{{ stock.reduce((p, c) => { return +p + +c.current_quantity }, 0) }}</strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>
<script>
    Vue.component('v-select', VueSelect.VueSelect);
    const app = new Vue({
        el: '#app',
        data: {
            products: [],
			selectedProduct: null,
            stock: []
        },
        watch: {
            async selectedProduct(product) {
                if(product == undefined) return;
                await this.getSearchResult(product.Product_SlNo)
            }
        },
        created() {
            this.getProducts();
        },
        methods: {
            getProducts(){
				axios.get('/get_products').then(res => {
					this.products = res.data;
				})
			},   
            async getSearchResult(id) {
                let productId = id
                if(productId == undefined || productId == null) return;

                await axios.post('/get_all_branch_stock', { productId: productId })
                .then(res => {
                    this.stock = res.data.map((item, sl) => {
                        item.sl = sl + 1;
                        return item;
                    });
                })
            }
        }
    }) 
</script>