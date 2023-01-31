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
<div id="replaceRecord">
    <div class="row" style="border-bottom: 1px solid #ccc;padding: 3px 0;margin-bottom:15px">
		<div class="col-md-12">
			<form class="form-inline" id="searchForm" @submit.prevent="getSaleReplaces">
				<div class="form-group">
                    <label for="dateFrom">Date From</label>
					<input type="date" class="form-control" v-model="dateFrom">
				</div>

				<div class="form-group">
                    <label for="dateTo">Date To</label>
					<input type="date" class="form-control" v-model="dateTo">
				</div>

				<div class="form-group" style="margin-top: -5px;">
					<input type="submit" value="Search">
				</div>
			</form>
		</div>
	</div>
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 10px;">
			<a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
		</div>

        <div class="col-md-12">
            <div class="table-responsive" id="reportContent">
                <table class="record-table">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Date</th>
                            <th>Customer Id</th>
                            <th>Customer Name</th>
                            <th>Mobile</th>
                            <th>Note</th>
                            <th>Save By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="sale in records">
                            <td>{{ sale.sl }}</td>
                            <td>{{ sale.date }}</td>
                            <td>{{ sale.Customer_Code }}</td>
                            <td>{{ sale.Customer_Name }}</td>
                            <td>{{ sale.Customer_Mobile }}</td>
                            <td width="30%">{{ sale.note }}</td>
                            <td>{{ sale.added_by }}</td>
                            <td>
                            <a href="" title="Replace Invoice" v-bind:href="`/replace_invoice/${sale.id}`" target="_blank"><i class="fa fa-file"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>

<script>
    const app = new Vue({
        el: '#replaceRecord',
        data: {
            dateFrom: moment().format('YYYY-MM-DD'),
			dateTo: moment().format('YYYY-MM-DD'),
            records: []
        },
        async created() {
            await this.getSaleReplaces();
        },
        methods: {  
            async getSaleReplaces() {
                let filter = {
                    dateFrom: this.dateFrom,
                    dateTo: this.dateTo
                }
                await axios.post('/get_replace_record', filter)
                .then(res => {
                    this.records = res.data.records.map((item, sl) => {
                        item.sl = sl + 1;
                        return item;
                    })
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
								<h3>Sales Replace Record</h3>
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
