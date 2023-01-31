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
        text-align: center;
	}
    .record-table th{
        text-align: center;
    }
</style>
<div id="invoices">
    <div class="col-md-8 col-md-offset-2">
        <div class="row">
            <div class="col-xs-12">
                <a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
            </div>
        </div>
        <div id="invoiceContent">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <div style="border-top: 1px dotted;border-bottom: 1px dotted;">
                        <h4>purchase Replace Invoice</h4>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 15px;">
                <div class="col-xs-7">
                    <strong>Supplier Id:</strong> {{ purchase.Supplier_Code }}<br>
                    <strong>Supplier Name:</strong> {{ purchase.Supplier_Name }}<br>
                    <strong>Supplier Address:</strong> {{ purchase.Supplier_Address }}<br>
                    <strong>Supplier Mobile:</strong> {{ purchase.Supplier_Mobile }}
                </div>
                <div class="col-xs-5 text-right">
                    <strong>Save By:</strong> {{ purchase.added_by }}<br>
                    <strong>Replace Date:</strong> {{ purchase.date }}<br>
                </div>
            </div>
            <div class="row" style="margin-top: 15px;">
                <div class="col-xs-12">
                    <table class="record-table">
                        <thead>
                            <tr>
                                <td>Sl.</td>
                                <td>Product Id</td>
                                <td>Product Name</td>
                                <td>Serial Out</td>
                                <td>Serial In</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product, sl) in cart">
                                <td>{{ sl + 1 }}</td>
                                <td>{{ product.Product_Code }}</td>
                                <td>{{ product.Product_Name }}</td>
                                <td>{{ product.serial_out }}</td>
                                <td>{{ product.serial_in }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    
            <div class="row" style="margin-top: 15px;">
                <div class="col-xs-12">
                    <strong>Note: </strong>
                    <p style="white-space: pre-line">{{ purchase.note }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>

<script>
    const app = new Vue({
        el: '#invoices',
        data: {
            replaceId: '<?php echo $replaceId?>',
            purchase: {},
            cart: []
        },
        async created() {
            this.getSaleReplaces();
        },
        methods: {
            async getSaleReplaces() {
                await axios.post('/get_purchase_replaces', { replaceId: this.replaceId })
                .then(res => {
                    this.purchase = res.data.records[0];
                    this.cart = res.data.details;
                })
            },
            async print(){
                let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#invoiceContent').innerHTML}
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
                            text-align: center;
						}
						.record-table th{
							text-align: center;
						}
					</style>
				`;
				reportWindow.document.body.innerHTML += reportContent;
			

				reportWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				reportWindow.print();
				reportWindow.close();
			}
        }
    })
</script>