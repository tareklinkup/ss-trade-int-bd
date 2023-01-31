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
</style>


<style type="text/css">
	.modal-mask {
position: fixed;
z-index: 9998;
top: 0;
left: 0;
width: 100%;
height: 100%;
background-color: rgba(0, 0, 0, .5);
display: table;
transition: opacity .3s ease;
}

.modal-wrapper {
display: table-cell;
vertical-align: middle;
}

.modal-container {
width: 400px;
margin: 0px auto;
background-color: #fff;
border-radius: 2px;
box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
transition: all .3s ease;
font-family: Helvetica, Arial, sans-serif;
}
.modal-header{
    padding-bottom: 0 !important;
}
.modal-header h3 {
margin-top: 0;
color: #42b983;
}

.modal-body {
margin: 0px 0;
}

.modal-default-button {
float: right;
}

/*
* The following styles are auto-applied to elements with
* transition="modal" when their visibility is toggled
* by Vue.js.
*
* You can easily play with the modal transition by editing
* these styles.
*/

.modal-enter {
opacity: 0;
}

.modal-leave-active {
opacity: 0;
}
	.modal-mask {
position: fixed;
z-index: 9998;
top: 0;
left: 0;
width: 100%;
height: 100%;
background-color: rgba(0, 0, 0, .5);
display: table;
transition: opacity .3s ease;
}

.modal-wrapper {
display: table-cell;
vertical-align: middle;
}

.modal-container {
width: 700px;
margin: 0px auto;
background-color: #fff;
border-radius: 2px;
box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
transition: all .3s ease;
font-family: Helvetica, Arial, sans-serif;
}
.modal-header{
    padding-bottom: 0 !important;
}
.modal-header h3 {
margin-top: 0;
color: #42b983;
}

.modal-body {
margin: 0px 0;
     overflow-y: auto !important;
    height: 300px !important;
    margin: -8px -14px -44px !important;

}
.modal-default-button {
float: right;
}
.modal-enter {
opacity: 0;
}

.modal-leave-active {
opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
-webkit-transform: scale(1.1);
transform: scale(1.1);
}

.modal-footer {
padding-top: 14px !important;
margin-top: 30px !important;
}
</style>


<div id="stock">

	<div style="display: none;"   id="serial-modal" v-if="" v-bind:style="{display:serialModalStatus?'block':'none'}" >
<transition name="modal">
<div class="modal-mask">
<div class="modal-wrapper">
<div class="modal-container"> 
<div class="modal-header">
	<slot name="header">
		<h3>Serial Number List</h3>
	</slot>
	</div>

	<div class="modal-body" style="overflow: hidden;
height: 100%;
margin: -8px -14px -44px;">
	<slot name="body">
		<div class="form-group">
<div class="col-sm-12" style="display: flex;
margin-bottom: 10px;">


</div>

</div>
	</slot>
	<table class="table">
<thead>
<tr>
<th scope="col">SL</th>
<th scope="col">Serial</th>
<th scope="col">Product</th>
<th scope="col">Purchase  Date</th>
</tr>
</thead>
<tbody>
  
<tr v-for="(serial, sl) in serials">
	<td>{{sl+1}}</td>
	<td>{{serial.ps_serial_number}}</td>
    <td> {{serial.Product_Name}}</td>
    <td> {{serial.purchase_date}}</td>
</tr>

</tbody>
</table>
	</div>
	<div class="modal-footer">
	<slot name="footer">
			<button class="modal-default-button" @click="serialHideModal" style="    background: #59b901;
border: none;
font-size: 18px;
color: white;">
		Close
		</button>
		
	</slot>
	</div>
</div>
</div>
</div>
</transition>
</div>
	<div class="row">
		<div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;margin-bottom:5px;">
			<div class="form-group" style="margin-top:10px;">
				<label class="col-sm-1 col-sm-offset-1 control-label no-padding-right"> Select Serial </label>
				<div class="col-sm-2"> 
					<v-select v-bind:options="serial_list" v-model="selectedSerial" label="ps_serial_number" v-on:input="onChangeSerial"></v-select>
				</div>
            </div> 
         
			<div class="form-group">
				<div class="col-sm-2"  style="margin-left:15px;">
					<input type="button" class="btn btn-primary" value="Show Report" v-on:click="getStock" style="margin-top:0px;border:0px;height:28px;">
				</div>
			</div>
		</div>
	</div>
	<div class="row" v-if="searchType != null" style="display:none" v-bind:style="{display: searchType == null ? 'none' : ''}">
		<div class="col-md-12">
			<a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
            <h6>Purchase Detail </h6> 
			<table class="table">
                   <tr v-for="(serial, sl) in serialReport.purchaseReport">
                       <td>Supplier Name : </td>
                       <td>{{  serial.Supplier_Name }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.purchaseReport">
                       <td>Supplier Code : </td>
                       <td>{{  serial.Supplier_Code }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.purchaseReport">
                       <td>Supplier Mobile : </td>
                       <td>{{  serial.Supplier_Mobile }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.purchaseReport">
                       <td>Purchase Invoice  : </td>
                       <td>{{  serial.invoiceNo }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.purchaseReport">
                       <td>Product Name  : </td>
                       <td>{{  serial.Product_Name }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.purchaseReport">
                       <td>Purchase Date  : </td>
                       <td>{{  serial.purchaseDate }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.purchaseReport">
                       <td>Warranty Month  : </td>
                       <td>{{ serial.product_warranty_month }} <small v-if="serial.product_warranty_month">Month(s)</small></td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.purchaseReport">
                       <td>Purchase Branch  : </td>
                       <td>{{ serial.Brunch_name }}</td>
                   </tr>
            </table>
        </div>
        
        <div class="col-md-6">
        <h6>Sale Detail </h6> 
			<table class="table">
                   
                   <tr v-for="(serial, sl) in serialReport.saleReport">
                       <td>Customer Name : </td>
                       <td>{{  serial.Customer_Name }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.saleReport">
                       <td>Customer Code : </td>
                       <td>{{  serial.Customer_Code }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.saleReport">
                       <td>Customer Mobile : </td>
                       <td>{{  serial.Customer_Mobile }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.saleReport">
                       <td>Sale Invoice  : </td>
                       <td>{{  serial.invoiceNo }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.saleReport">
                       <td>Product Name  : </td>
                       <td>{{  serial.Product_Name }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.saleReport">
                       <td>Sale Date  : </td>
                       <td>{{  serial.saleDate }}</td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.saleReport">
                       <td>Warranty : </td>
                       <td><span v-if="serial.warranty_month">{{ serial.warranty_date }}</span></td>
                   </tr>
                   <tr v-for="(serial, sl) in serialReport.saleReport">
                       <td>Sales Branch : </td>
                       <td>{{ serial.Brunch_name }}</td>
                   </tr>
            </table>
		</div>
	</div>
</div>





<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>

<script>
	Vue.component('modal', {
	template: '#serial-modal'
	})
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#stock',
		data(){
			return {
				searchTypes: [
					{text: 'Current Stock', value: 'current'},
					{text: 'Total Stock', value: 'total'},
					{text: 'Category Wise Stock', value: 'category'},
					{text: 'Product Wise Stock', value: 'product'},
					//{text: 'Brand Wise Stock', value: 'brand'}
				],
				selectedSearchType: {
					text: 'select',
					value: ''
				},
				searchType: null,
				categories: [],
				selectedCategory: null,
				products: [],
				selectedProduct: null,
				brands: [],
				selectedBrand: null,
				selectionText: '',
				serialModalStatus: false,
				stock: [],
				serials:[],
				totalStockValue: 0.00,
				current_quantity:0,
                serial_list:[],
                selectedSerial:null,
                serialReport:[]

			}
		},
		created(){
            this.getSerialS();
		},
		methods:{
            async getSerialS(){
                  await axios.post(`/get_serial_list`).then((res)=>{
                         this.serial_list = res.data;
                  })
            },
            onChangeSerial(){

            },
			serialShowModal(){

            this.serialModalStatus = true;
            console.log(this.serialModalStatus);
		    },
		    serialHideModal(){
	       this.serialModalStatus = false;
		   },
			async getStock(){
				await axios.post('/get_serial_report',{serial:this.selectedSerial.ps_serial_number})
				.then((res)=>{
					this.serialReport = res.data;
				});
			},
			async viewSerial(prod_id){
				await axios.post('/get_Serial_By_Prod',{prod_id:prod_id})
				.then((res)=>{
					this.serials = res.data;
				});
				this.serialShowModal();
			},
			onChangeSearchType(){
				if(this.selectedSearchType.value == 'category' && this.categories.length == 0){
					this.getCategories();
				} else if(this.selectedSearchType.value == 'brand' && this.brands.length == 0){
					this.getBrands();
				} else if(this.selectedSearchType.value == 'product' && this.products.length == 0){
					this.getProducts();
				}
			},
			getCategories(){
				axios.get('/get_categories').then(res => {
					this.categories = res.data;
				})
			},
			getProducts(){
				axios.get('/get_products').then(res => {
					this.products =  res.data;
				})
			},
			getBrands(){
				axios.get('/get_brands').then(res => {
					this.brands = res.data;
				})
			},
			async print(){
				let reportContent = `
					<div class="container">
						<h4 style="text-align:center">${this.selectedSearchType.text} Report</h4 style="text-align:center">
						<h6 style="text-align:center">${this.selectionText}</h6>
					</div>
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#stockContent').innerHTML}
							</div>
						</div>
					</div>
				`;

				var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}, left=0, top=0`);
				reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
				`);

				reportWindow.document.body.innerHTML += reportContent;

				reportWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				reportWindow.print();
				reportWindow.close();
			}
		}
	})
</script>