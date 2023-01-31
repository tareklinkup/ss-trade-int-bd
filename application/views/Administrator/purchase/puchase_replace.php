<style>
	.v-select{
		margin-bottom: 5px;
	}
	.v-select .dropdown-toggle{
		padding: 0px;
	}
	.v-select input[type=search], .v-select input[type=search]:focus{
		margin: 0px;
	}
	.v-select .vs__selected-options{
		overflow: hidden;
		flex-wrap:nowrap;
	} 
	.v-select .selected-tag{
		margin: 2px 0px;
		white-space: nowrap;
		position:absolute;
		left: 0px;
	}
	.v-select .vs__actions{
		margin-top:-5px;
	}
	.v-select .dropdown-menu{
		width: auto;
		overflow-y:auto;
	}
	#branchDropdown .vs__actions button{
		display:none;
	}
	#branchDropdown .vs__actions .open-indicator{
		height:15px;
		margin-top:7px;
	}
	#payment_type {
		width: 100%;
		border-radius: 5px;
		height: 26px;
	}
</style>
<div id="sales" class="row">
	<div class="col-xs-12 col-md-8 col-lg-8 col-sm-8">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="widget-title">Purchase Information</h4>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse">
						<i class="ace-icon fa fa-chevron-up"></i>
					</a>

					<a href="#" data-action="close">
						<i class="ace-icon fa fa-times"></i>
					</a>
				</div>
			</div>

			<div class="widget-body">
				<div class="widget-main">

					<div class="row">
						<div class="col-sm-6">
							
							<div class="form-group">
								<label class="col-sm-4 control-label no-padding-right"> Supplier </label>
								<div class="col-sm-7">
									<v-select v-bind:options="suppliers" label="display_name" v-model="selectedSupplier"></v-select>
								</div>
								<div class="col-sm-1" style="padding: 0;">
									<a href="<?= base_url('supplier')?>" class="btn btn-xs btn-danger" style="height: 25px; border: 0; width: 27px; margin-left: -10px;" target="_blank" title="Add New supplier"><i class="fa fa-plus" aria-hidden="true" style="margin-top: 5px;"></i></a>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label no-padding-right"> Mobile No </label>
								<div class="col-sm-8">
									<input type="text" id="mobileNo" placeholder="Mobile No" class="form-control" v-model="selectedSupplier.Supplier_Mobile" v-bind:disabled="selectedSupplier.supplier_Type == 'G' ? false : true" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label no-padding-right"> Address </label>
								<div class="col-sm-8">
									<textarea id="address" placeholder="Address" class="form-control" v-model="selectedSupplier.Supplier_Address" v-bind:disabled="selectedSupplier.supplier_Type == 'G' ? false : true"></textarea>
								</div>
							</div>
						</div>

						<div class="col-sm-6">
							<form v-on:submit.prevent="addToCart">
							
								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right"> Product </label>
									<div class="col-sm-8">
										<v-select v-bind:options="products" v-model="selectedProduct" label="display_text"></v-select>
									</div>
									<div class="col-sm-1" style="padding: 0;">
										<a href="/product" class="btn btn-xs btn-danger" style="height: 25px; border: 0; width: 27px; margin-left: -10px;" target="_blank" title="Add New Product"><i class="fa fa-plus" aria-hidden="true" style="margin-top: 5px;"></i></a>
									</div>
								</div>

                                <div class="form-group">
									<label class="col-sm-3 control-label no-padding-right"> Serial Out </label>
									<div class="col-sm-9"> 
										<v-select v-bind:options="saleSerial" v-model="serialOut" label="ps_serial_number"></v-select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right"> Serial In </label>
									<div class="col-sm-9"> 
										<input type="text" class="form-control" v-model="serialIn" required>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right"> </label>
									<div class="col-sm-9">
										<button type="submit" class="btn btn-default pull-right">Add to Cart</button>
									</div>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-md-12 col-lg-12" style="padding-left: 0px;padding-right: 0px;">
			<div class="table-responsive">
				<table class="table table-bordered" style="color:#000;margin-bottom: 5px;">
					<thead>
						<tr class="">
							<th style="width:10%;color:#000;">Sl</th>
							<th style="width:20%;color:#000;">Product Name</th>
							<th style="color:#000;">Out Imei No.</th>
							<th style="color:#000;">In Imei No.</th>
							<th style="width:15%;color:#000;">Action</th>
						</tr>
					</thead>
					<tbody style="display:none;" v-bind:style="{display: cart.length > 0 ? '' : 'none'}">
						<tr v-for="(product, sl) in cart"> 
							<td>{{ sl + 1 }}</td>
							<td>{{ product.productName }}</td>
							<td>{{ product.serialOut }}</td>
							<td>{{ product.serialIn }}</td>
							<td><a href="" v-on:click.prevent="removeFromCart(sl)"><i class="fa fa-trash"></i></a></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="widget-box">
			<div class="widget-header">
				<h4 class="widget-title">Replace Inforamtion</h4>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse">
						<i class="ace-icon fa fa-chevron-up"></i>
					</a>

					<a href="#" data-action="close">
						<i class="ace-icon fa fa-times"></i>
					</a>
				</div>
			</div>

			<div class="widget-body">
				<div class="widget-main">
					<div class="row">
                        <div class="form-group">
                            <label for="date" class="col-md-3">Date:</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" v-model="replace.date">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="date" class="col-md-3">Note:</label>
                            <div class="col-md-9">
                               <textarea rows="4" class="form-control" v-model="replace.note"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="date" class="col-md-3"></label>
                            <div class="col-md-9">
                               <input type="submit" value="Save" @click.prevent="saveReplace" class="btn btn-info btn-sm btn-block">
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#sales',
		data(){
			return {
				replace:{
					id: null,
					date: moment().format('YYYY-MM-DD'),
					supplierId: '',
					note: '',
				},
				serialIn: '',
				saleSerial: [],
				serialOut: null,
				cart: [],
				employees: [],
                selectedEmployee: null,
				
				suppliers: [],
				selectedSupplier:{
					Supplier_SlNo: null,
					Supplier_Code: '',
					Supplier_Name: '',
					display_name: 'Select Supplier',
					Supplier_Mobile: '',
					Supplier_Address: '',
					Supplier_Type: ''
				},

				products: [],
				selectedProduct: null,
				
				saleOnProgress: false,
			}
		},
		watch: {
            selectedSupplier(supplier) {
                if(supplier == undefined) return;
                this.selectedSupplier.Supplier_Mobile = supplier.Supplier_Mobile;
                this.selectedSupplier.Supplier_Address = supplier.Supplier_Address;
                this.replace.supplierId = supplier.Supplier_SlNo;
            },
			async selectedProduct(product) {
				if(product == undefined) return;
				await this.getProductSerial(product.Product_SlNo);
			}
		},
		created(){
            this.getSuppliers();
            this.getProducts();
		},
		methods:{
			getSuppliers() {
				axios.get('/get_suppliers').then(res => {
					this.suppliers = res.data;
				})
			},
            getProducts(){
				axios.get('/get_products').then(res => {
					this.products = res.data;
				})
			},
			async getProductSerial(productId) {
				await axios.post('/get_replace_serial', { productId: productId})
				.then(res => {
					this.saleSerial = res.data
				})
			},
			addToCart(){
				if(this.selectedProduct.Product_SlNo == '') {
					alert('Select product');
					return;
				}
				if(this.serialOut == null) {
					alert('Select serial out first');
					return;
				}
				if(this.serialIn == '') {
					alert('Serial in is required');
					return;
				}

				let product = {
					productId: this.selectedProduct.Product_SlNo,
					productName: this.selectedProduct.Product_Name,
					serialIn: this.serialIn,
					serialOut: this.serialOut.ps_serial_number
				}

				let checkSerialIn = this.cart.findIndex(item => item.productId == this.selectedProduct.Product_SlNo &&  item.serialIn == this.serialIn)
				let checkSerialOut = this.cart.findIndex(item => item.productId == this.selectedProduct.Product_SlNo &&  item.serialOut == this.serialOut.ps_serial_number)
				
				if(checkSerialIn > -1) {
					alert('Already serial in add to cart');
					return;
				}
				
				if(checkSerialOut > -1) {
					alert('Already serial out add to cart');
					return;
				}

				this.cart.push(product)
				this.resetForm();
			},
			removeFromCart(ind) {
				this.cart.splice(ind, 1)
			},
			resetForm(){
				this.selectedProduct = null;
				this.serialIn = '';
				this.serialOut = null;
				this.saleSerial = [];
			},
			async saveReplace(){
				if(this.replace.supplierId == '') {
					alert('Select supplier');
					return;
				}
				if(this.cart.length == 0) {
					alert('Cart is empty');
					return;
				}

				let data = {
					cart: this.cart,
					replace: this.replace
				}

				await axios.post('/add_purchae_replace', data)
				.then(res => {
					if(res.data.success) {
						alert(res.data.message);
						this.clearData();
					}
				})
				.catch(err => {
					console.log(err.response.data.message);
				})
			},

			clearData(){
				this.replace.id = null;
				this.replace.date = moment().format('YYYY-MM-DD');
				this.replace.supplierId = '';
				this.replace.note = '';
				this.cart = [];
				this.selectedSupplier = {
					Supplier_SlNo: null,
					Supplier_Code: '',
					Supplier_Name: '',
					display_name: 'Select Supplier',
					Supplier_Mobile: '',
					Supplier_Address: '',
					Supplier_Type: ''
				}
			}
		}
	})
</script>