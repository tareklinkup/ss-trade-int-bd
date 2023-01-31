<style>
    .v-select{
		margin-top:-2.5px;
        float: right;
        min-width: 180px;
        margin-left: 5px;
	}
	.v-select .dropdown-toggle{
		padding: 0px;
        height: 25px;
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
	#searchForm select{
		padding:0;
		border-radius: 4px;
	}
	#searchForm .form-group{
		margin-right: 5px;
	}
	#searchForm *{
		font-size: 13px;
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
	}
    .record-table th{
        text-align: center;
    }
</style>
<div id="branchwiseSalesRecord">
	<div class="row" style="border-bottom: 1px solid #ccc;padding: 3px 0;">
		<div class="col-md-12">
			<form class="form-inline" id="searchForm" @submit.prevent="getSearchResult">
				<div class="form-group">
					<label>Branch</label>
					<v-select v-bind:options="branches" v-model="selectedBranch" label="Brunch_name"></v-select>
				</div>				

				<div class="form-group">
					<label>Record Type</label>
					<select class="form-control" v-model="recordType" @change="sales = []">
						<option value="without_details">Without Details</option>
						<option value="with_details">With Details</option>
					</select>
				</div>

				<div class="form-group">
					<input type="date" class="form-control" v-model="dateFrom">
				</div>

				<div class="form-group">
					<input type="date" class="form-control" v-model="dateTo">
				</div>

				<div class="form-group" style="margin-top: -5px;">
					<input type="submit" value="Search">
				</div>
			</form>
		</div>
	</div>

	<div class="row" style="margin-top:15px;display:none;" v-bind:style="{display: sales.length > 0 ? '' : 'none'}">
		<div class="col-md-12" style="margin-bottom: 10px;">
			<a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
		</div>
		<div class="col-md-12">
			<div class="table-responsive" id="reportContent">
				<table 
					class="record-table" 
					v-if="recordType == 'with_details'" 
					style="display:none" 
					v-bind:style="{display: recordType == 'with_details' ? '' : 'none'}"
					>
					<thead>
						<tr>
							<th>Invoice No.</th>
							<th>Date</th>
							<th>Customer Name</th>
							<th>Employee Name</th>
							<th>Saved By</th>
							<th>Product Name</th>
							<th>Price</th>
							<th>Quantity</th>
							<th>Discount</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<template v-for="sale in sales">
							<tr>
								<td>{{ sale.SaleMaster_InvoiceNo }}</td>
								<td>{{ sale.SaleMaster_SaleDate }}</td>
								<td>{{ sale.Customer_Name }}</td>
								<td>{{ sale.Employee_Name }}</td>
								<td>{{ sale.AddBy }}</td>
								<td>{{ sale.saleDetails[0].Product_Name }}</td>
								<td style="text-align:right;">{{ sale.saleDetails[0].SaleDetails_Rate }}</td>
								<td style="text-align:center;">{{ sale.saleDetails[0].SaleDetails_TotalQuantity }}</td>
								
								<td style="text-align:center;">{{ sale.saleDetails[0].SaleDetails_Discount }}</td>
								<td style="text-align:right;">{{ sale.saleDetails[0].SaleDetails_TotalAmount }}</td>
							</tr>
							<tr v-for="(product, sl) in sale.saleDetails.slice(1)">
								<td colspan="5" v-bind:rowspan="sale.saleDetails.length - 1" v-if="sl == 0"></td>
								<td>{{ product.Product_Name }}</td>
								<td style="text-align:right;">{{ product.SaleDetails_Rate }}</td>
								<td style="text-align:center;">{{ product.SaleDetails_TotalQuantity }}</td>
								<td style="text-align:center;">{{ product.SaleDetails_Discount }}</td>
								<td style="text-align:right;">{{ product.SaleDetails_TotalAmount }}</td>
								<td></td>
							</tr>
							<tr style="font-weight:bold;">
								<td colspan="7"></td>
								<td style="text-align:center;">Total Quantity<br>{{ sale.saleDetails.reduce((prev, curr) => {return prev + parseFloat(curr.SaleDetails_TotalQuantity)}, 0) }}</td>
								<td></td>
								<td style="text-align:right;">
									Total: {{ sale.SaleMaster_TotalSaleAmount }}<br>
									Paid: {{ sale.SaleMaster_PaidAmount }}<br>
									Due: {{ sale.SaleMaster_DueAmount }}
								</td>
							</tr>
						</template>
					</tbody>
				</table>

				<table 
					class="record-table" 
					v-if="recordType == 'without_details'" 
					style="display:none" 
					v-bind:style="{display: recordType == 'without_details' ? '' : 'none'}"
					>
					<thead>
						<tr>
							<th>Invoice No.</th>
							<th>Date</th>
							<th>Customer Name</th>
							<th>Employee Name</th>
							<th>Saved By</th>
							<th>Sub Total</th>
							<th>VAT</th>
							<th>Discount</th>
							<th>Transport Cost</th>
							<th>Total</th>
							<th>Paid</th>
							<th>Due</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="sale in sales">
							<td>{{ sale.SaleMaster_InvoiceNo }}</td>
							<td>{{ sale.SaleMaster_SaleDate }}</td>
							<td>{{ sale.Customer_Name }}</td>
							<td>{{ sale.Employee_Name }}</td>
							<td>{{ sale.AddBy }}</td>
							<td style="text-align:right;">{{ sale.SaleMaster_SubTotalAmount }}</td>
							<td style="text-align:right;">{{ sale.SaleMaster_TaxAmount }}</td>
							<td style="text-align:right;">{{ sale.SaleMaster_TotalDiscountAmount }}</td>
							<td style="text-align:right;">{{ sale.SaleMaster_Freight }}</td>
							<td style="text-align:right;">{{ sale.SaleMaster_TotalSaleAmount }}</td>
							<td style="text-align:right;">{{ sale.SaleMaster_PaidAmount }}</td>
							<td style="text-align:right;">{{ sale.SaleMaster_DueAmount }}</td>
						</tr>
					</tbody>
					<tfoot>
						<tr style="font-weight:bold;">
							<td colspan="5" style="text-align:right;">Total</td>
							<td style="text-align:right;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_SubTotalAmount)}, 0) }}</td>
							<td style="text-align:right;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_TaxAmount)}, 0) }}</td>
							<td style="text-align:right;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_TotalDiscountAmount)}, 0) }}</td>
							<td style="text-align:right;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_Freight)}, 0) }}</td>
							<td style="text-align:right;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_TotalSaleAmount)}, 0) }}</td>
							<td style="text-align:right;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_PaidAmount)}, 0) }}</td>
							<td style="text-align:right;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_DueAmount)}, 0) }}</td>
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
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#branchwiseSalesRecord',
		data(){
			return {
				searchType: '',
				recordType: 'without_details',
				dateFrom: moment().format('YYYY-MM-DD'),
				dateTo: moment().format('YYYY-MM-DD'),
				branches: [],
				selectedBranch: null,
				sales: [],
				isService: 'false'
			}
		},
        created() {
            this.getBranches();
        },
		methods: {
			getBranches(){
				axios.get('/get_branches').then(res => {
					this.branches = res.data;
				})
			},
			
			getSearchResult(){
                this.getSalesRecord();
			},
			getSalesRecord(){
				let filter = {
					branchId: this.selectedBranch == null || this.selectedBranch.brunch_id == '' ? '' : this.selectedBranch.brunch_id,
					dateFrom: this.dateFrom,
					dateTo: this.dateTo,
					isService: this.isService
				}

				let url = '/get_sales';
				if(this.recordType == 'with_details'){
					url = '/get_sales_record';
				}

				axios.post(url, filter)
				.then(res => {
					if(this.recordType == 'with_details'){
						this.sales = res.data;
					} else {
						this.sales = res.data.sales;
					}
				})
				.catch(error => {
					if(error.response){
						alert(`${error.response.status}, ${error.response.statusText}`);
					}
				})
			},
			async print(){
				let dateText = '';
				if(this.dateFrom != '' && this.dateTo != ''){
					dateText = `Statemenet from <strong>${this.dateFrom}</strong> to <strong>${this.dateTo}</strong>`;
				}

				let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12 text-center">
								<h3>Sales Record</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
							</div>
							<div class="col-xs-6 text-right">
								${dateText}
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#reportContent').innerHTML}
							</div>
						</div>
					</div>
				`;

				var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
				reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
				`);

				reportWindow.document.head.innerHTML += `
					<style>
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
						}
						.record-table th{
							text-align: center;
						}
					</style>
				`;
				reportWindow.document.body.innerHTML += reportContent;
				
                let rows = reportWindow.document.querySelectorAll('.record-table tr');
                rows.forEach(row => {
                    row.lastChild.remove();
                })

				reportWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				reportWindow.print();
				reportWindow.close();
			}
		}
	})
</script>