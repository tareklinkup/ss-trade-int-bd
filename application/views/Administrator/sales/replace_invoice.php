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
                        <h4>Sales Replace Invoice</h4>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 15px;">
                <div class="col-xs-7">
                    <strong>Customer Id:</strong> {{ sales.Customer_Code }}<br>
                    <strong>Customer Name:</strong> {{ sales.Customer_Name }}<br>
                    <strong>Customer Address:</strong> {{ sales.Customer_Address }}<br>
                    <strong>Customer Mobile:</strong> {{ sales.Customer_Mobile }}
                </div>
                <div class="col-xs-5 text-right">
                    <strong>Save By:</strong> {{ sales.added_by }}<br>
                    <strong>Replace Date:</strong> {{ sales.date }}<br>
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
                                <td>Serial In</td>
                                <td>Serial Out</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product, sl) in cart">
                                <td>{{ sl + 1 }}</td>
                                <td>{{ product.Product_Code }}</td>
                                <td>{{ product.Product_Name }}</td>
                                <td>{{ product.serial_in }}</td>
                                <td>{{ product.serial_out }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    
            <div class="row" style="margin-top: 15px;">
                <div class="col-xs-12">
                    <strong>Note: </strong>
                    <p style="white-space: pre-line">{{ sales.note }}</p>
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
            sales: {},
            cart: []
        },
        async created() {
            this.getSaleReplaces();
        },
        methods: {
            async getSaleReplaces() {
                await axios.post('/get_replace_record', { replaceId: this.replaceId })
                .then(res => {
                    this.sales = res.data.records[0];
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