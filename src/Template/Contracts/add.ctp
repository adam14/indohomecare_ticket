<?php $this->start('style'); ?>
<?php echo $this->Html->css('/vendor/bootstrap-datepicker/css/bootstrap-datepicker'); ?>
<?php $this->end(); ?>
<?php $this->start('script'); ?>
<?php echo $this->Html->script('/vendor/bootstrap-datepicker/js/bootstrap-datepicker'); ?>
<?php echo $this->Html->script('/vendor/parsley/js/parsley.min'); ?>
<script>
	$(document).ready(function() {
        $(".date").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            minView: 2,
            weekStart: 1,
            language: 'en',
            startDate: '',
            endDate: '',
            todayHighlight: true,
	    });

        $('.number').keyup(function() {
            this.value = this.value.replace(/[^0-9\.]/g,'');
        });

		$('#Code').bind('change keyup input', function() {
			this.value = this.value.toUpperCase();
		});

        $("#PJ").on('change', function() {
            var pj_id = $(this).val();
            var data_pj = {
                'pj_id' : pj_id
            };

            $.ajax({
                type: 'POST',
                url: '<?php echo $this->Url->build(['controller' => 'Contracts', 'action' => 'getPatient']); ?>',
                data: data_pj,
                dataType: "json",
                beforeSend: function() {
                    $('#Patient').empty();
                    $('#Patient').append(new Option('Loading...', ''));
                },
                success: function(result) {
                    $('#Patient').empty();
                    $('#Patient').append(new Option('-- Silakan Pilih --', ''));

                    for (i = 0; i < result.data.length; i++) {
                        $('#Patient').append('<option value="'+ result.data[i].id +'">'+ result.data[i].fullname +'</option>')
                    }

                    $.ajax({
                        type: 'POST',
                        url: '<?php echo $this->Url->build(['controller' => 'Contracts', 'action' => 'detailPj']); ?>',
                        data: data_pj,
                        dataType: "json",
                        success: function(result) {                            
                            if (result.status == 'true') {
                                $('#NamaLengkap').val(result.data.fullname);
                                $('#NomorTelepon').val(result.data.handphone);
                                $('#KTP').val(result.data.ktp);
                                $('#Email').val(result.data.email);
                                $('#Alamat').val(result.data.address);
                            } else {
                                $('#NamaLengkap').val('');
                                $('#NomorTelepon').val('');
                                $('#KTP').val('');
                                $('#Email').val('');
                                $('#Alamat').val('');
                            }
                        }
                    });
                }
            });
        });

        $('#Patient').on('change', function() {
            var patient_id = $(this).val();
            var data_patient = {
                'patient_id' : patient_id
            };

            $.ajax({
                type: 'POST',
                url: '<?php echo $this->Url->build(['controller' => 'Contracts', 'action' => 'detailPatient']); ?>',
                data: data_patient,
                dataType: "json",
                success: function(result) {
                    if (result.status == 'true') {
                        $('#RekomendasiDari').val(result.data.recomendation_from);
                        $('#JenisKelamin').val(result.data.gender);
                        $('#Umur').val(result.data.years);
                        $('#BeratBadan').val(result.data.height);
                        $('#TinggiBadan').val(result.data.weight);
                        $('#AlamatLengkap').val(result.data.address);
                        $('#AlatTerpasang').val(result.data.attached_tools);
                        $('#Diagnosa').val(result.data.diagnosis);
                        $('#KeluhanUtama').val(result.data.main_complaint);
                    } else {
                        $('#RekomendasiDari').val('');
                        $('#JenisKelamin').val('');
                        $('#Umur').val('');
                        $('#BeratBadan').val('');
                        $('#TinggiBadan').val('');
                        $('#AlamatLengkap').val('');
                        $('#AlatTerpasang').val('');
                        $('#Diagnosa').val('');
                        $('#KeluhanUtama').val('');
                    }
                }
            });
        });
	});
</script>
<?php echo $this->element('Contract/contract_script'); ?>
<?php $this->end(); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-body">
                <h4 class="page-head-line">Form Kontrak</h4>
				<div class="tab-content">
					<div id="Detail" class="tab-pane fade in active">
                        <?php echo $this->Form->create(null, ['url' => ['action' => 'add', $contracts['contract_no']], 'type' => 'file', 'data-parsley-validate']); ?>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 margin-bottom-30">
                                    <h4>Data Kontrak</h4>
                                    <div class="panel panel-primary">
                                        <div class="panel-body">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Nomor Kontrak</label>
                                                    <input type="text" class="form-control input-sm" name="contract_no" id="ContractNo" value="<?php echo $contracts['contract_no'] ?>" readonly>
                                                    <input type="hidden" class="form-control input-sm" name="contract_id" id="ContractID" value="<?php echo $contracts['id']; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tanggal Mulai</label>
                                                    <input type="text" name="start_date" id="StartDate" class="form-control input-sm date" autocomplete="off" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tanggal Berakhir</label>
                                                    <input type="text" name="end_date" id="EndDate" class="form-control input-sm date" autocomplete="off" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Dibuat Oleh</label>
                                                    <input type="text" class="form-control input-sm" value="<?php echo $contracts['users']['fullname']; ?>" readonly>
                                                    <input type="hidden" class="form-control input-sm" value="<?php echo $contracts['users']['id']; ?>" name="created_by">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tanggal</label>
                                                    <input type="text" class="form-control input-sm" value="<?php echo date('d-m-Y', strtotime($contracts['created_at'])); ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Jumlah Biaya</label>
                                                    <input type="text" class="form-control input-sm" id="JumlahBiaya" readonly>
                                                    <input type="hidden" class="form-control input-sm" id="TotalPriceContract" name="total_price">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <p id="SubtotalPriceNurseContract"></p>
                                                <p id="SubtotalPriceTherapistContract"></p>
                                                <p id="SubtotalPriceMedicToolContract"></p>
                                                <p id="SubtotalPriceTransportContract"></p>
                                                <p id="SubtotalPriceEventContract"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 margin-bottom-30">
                                    <h4>Data PJ</h4>
                                    <div class="panel panel-primary">
                                        <div class="panel-body">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>PJ</label>
                                                    <select name="pj_id" id="PJ" class="form-control input-sm">
                                                        <option value="">--Silahkan Pilih--</option>
                                                        <?php foreach ($pjs as $value): ?>
                                                            <option value="<?php echo $value['id']; ?>"><?php echo $value['fullname']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Lengkap</label>
                                                    <input type="text" id="NamaLengkap" class="form-control input-sm" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nomor Telepon</label>
                                                    <input type="text" id="NomorTelepon" class="form-control input-sm" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>KTP</label>
                                                    <input type="text" id="KTP" class="form-control input-sm" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="text" id="Email" class="form-control input-sm" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Alamat</label>
                                                    <textarea id="Alamat" class="form-control input-sm" rows="4" readonly></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 margin-bottom-30">
                                    <h4>Data Layanan</h4>
                                    <div class="panel panel-primary">
                                        <div class="panel-body">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a data-toggle="tab" href="#perawat" class="menu-tab">Perawat</a></li>
                                                <li><a data-toggle="tab" href="#therapist" class="menu-tab">Therapist</a></li>
                                                <li><a data-toggle="tab" href="#alkes" class="menu-tab">Alkes</a></li>
                                                <li><a data-toggle="tab" href="#transport" class="menu-tab">Transport</a></li>
                                                <li><a data-toggle="tab" href="#event" class="menu-tab">Event</a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <div id="perawat" class="tab-pane fade in active">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="panel panel-default">
                                                                <div class="panel-body">
                                                                    <div id="AlertNurseContract" hidden="true"></div>
                                                                    <?php echo $this->Html->link('Add New', 'javascript:;', ['class' => 'disable btn btn-sm btn-success', 'id' => 'ButtonAddContractNurse', 'title' => 'Click to Add', 'escape' => false]); ?>
                                                                    <input type="hidden" id="TotalPriceNurseContract" class="form-control input-sm" value="0">
                                                                    <div class="row">
                                                                        <div id="NurseAdd" class="col-md-12 margin-bottom-30">
                                                                            <div class="panel panel-primary">
                                                                                <div class="panel-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label>Kategori Perawat</label>
                                                                                                <select id="NurseCategory" class="form-control input-sm">
                                                                                                    <option value="">-- Silakan Pilih --</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Perawat</label>
                                                                                                <select id="Nurses" class="form-control input-sm">
                                                                                                    <option value="">-- Silakan Pilih --</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Sesi Perawat</label>
                                                                                                <select id="NurseSessions" class="form-control input-sm">
                                                                                                    <option value="">-- Silakan Pilih --</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <button type="button" class="btn btn-sm btn-success" id="SaveAddContractNurse">Simpan</button>
                                                                                            <button type="button" class="btn btn-sm btn-default" id="CancelAddContractNurse">Batal</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div id="NurseList" class="col-md-12 margin-bottom-30">
                                                                            <div class="panel panel-primary">
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-striped table-hover">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>Nama Perawat</th>
                                                                                                <th>Sesi Perawat</th>
                                                                                                <th>Harga</th>
                                                                                                <th>&nbsp;</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody id="bodyNurse">
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="therapist" class="tab-pane fade in">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="panel panel-default">
                                                                <div class="panel-body">
                                                                    <div id="AlertTherapistContract" hidden="true"></div>
                                                                    <?php echo $this->Html->link('Add New', 'javascript:;', ['class' => 'disable btn btn-sm btn-success', 'id' => 'ButtonAddTherapistContract', 'title' => 'Click to Add', 'escape' => false]); ?>
                                                                    <input type="hidden" id="TotalPriceTherapistContract" class="form-control input-sm" value="0">
                                                                    <div class="row">
                                                                        <div id="TherapistAdd" class="col-md-12 margin-bottom-30">
                                                                            <div class="panel panel-primary">
                                                                                <div class="panel-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label>Tipe Terapi</label>
                                                                                                <select id="TherapistType" class="form-control input-sm">
                                                                                                    <option value="">-- Silakan Pilih --</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Terapi</label>
                                                                                                <select id="Therapist" class="form-control input-sm">
                                                                                                    <option value="">-- Silakan Pilih --</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Sesi Terapi</label>
                                                                                                <select id="TherapistSessions" class="form-control input-sm">
                                                                                                    <option value="">-- Silakan Pilih --</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <button type="button" class="btn btn-sm btn-success" id="SaveAddTherapistContract">Simpan</button>
                                                                                            <button type="button" class="btn btn-sm btn-default" id="CancelAddTherapistContract">Batal</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div id="TherapistList" class="col-md-12 margin-bottom-30">
                                                                            <div class="panel panel-primary">
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-striped table-hover">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>Nama Terapi</th>
                                                                                                <th>Sesi Terapi</th>
                                                                                                <th>Harga</th>
                                                                                                <th>&nbsp;</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody id="bodyTherapist">
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="alkes" class="tab-pane fade in">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="panel panel-default">
                                                                <div class="panel-body">
                                                                    <div id="AlertMedicToolContract" hidden="true"></div>
                                                                    <?php echo $this->Html->link('Add New', 'javascript:;', ['class' => 'disable btn btn-sm btn-success', 'id' => 'ButtonAddMedicToolContract', 'title' => 'Click to Add', 'escape' => false]); ?>
                                                                    <input type="hidden" id="TotalPriceMedicToolContract" class="form-control input-sm" value="0">
                                                                    <div class="row">
                                                                        <div id="MedicToolAdd" class="col-md-12 margin-bottom-30">
                                                                            <div class="panel panel-primary">
                                                                                <div class="panel-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label>Alkes</label>
                                                                                                <select id="MedicTool" class="form-control input-sm">
                                                                                                    <option value="">-- Silakan Pilih --</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Sesi Alkes</label>
                                                                                                <select id="MedicToolSessions" class="form-control input-sm">
                                                                                                    <option value="">-- Silakan Pilih --</option>
                                                                                                </select>
                                                                                                <input type="hidden" id="PriceMedicToolSessions" class="form-control input-sm">
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Jumlah</label>
                                                                                                <input type="text" id="Quantity" class="form-control input-sm number">
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Total Harga</label>
                                                                                                <input type="text" id="FormTotalPriceMedicToolContract" class="form-control input-sm" readonly>
                                                                                                <input type="hidden" id="SaveTotalPriceMedicToolContract" class="form-control input-sm">
                                                                                            </div>
                                                                                            <button type="button" class="btn btn-sm btn-success" id="SaveAddMedicToolContract">Simpan</button>
                                                                                            <button type="button" class="btn btn-sm btn-default" id="CancelAddMedicToolContract">Batal</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div id="MedicToolList" class="col-md-12 margin-bottom-30">
                                                                            <div class="panel panel-primary">
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-striped table-hover">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>Nama Alkes</th>
                                                                                                <th>Harga</th>
                                                                                                <th>Jumlah</th>
                                                                                                <th>Total Harga</th>
                                                                                                <th>&nbsp;</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody id="bodyMedicTool">
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="transport" class="tab-pane fade in">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="panel panel-default">
                                                                <div class="panel-body">
                                                                    <div id="AlertTransportContract" hidden="true"></div>
                                                                    <?php echo $this->Html->link('Add New', 'javascript:;', ['class' => 'disable btn btn-sm btn-success', 'id' => 'ButtonAddTransportContract', 'title' => 'Click to Add', 'escape' => false]); ?>
                                                                    <input type="hidden" id="TotalPriceTransportContract" class="form-control input-sm" value="0">
                                                                    <div class="row">
                                                                        <div id="TransportAdd" class="col-md-12 margin-bottom-30">
                                                                            <div class="panel panel-primary">
                                                                                <div class="panel-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label>Transport</label>
                                                                                                <select id="Transport" class="form-control input-sm">
                                                                                                    <option value="">-- Silakan Pilih --</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Jarak</label>
                                                                                                <input type="text" id="Distance" class="form-control input-sm number">
                                                                                            </div>
                                                                                            <button type="button" class="btn btn-sm btn-success" id="SaveAddTransportContract">Simpan</button>
                                                                                            <button type="button" class="btn btn-sm btn-default" id="CancelAddTransportContract">Batal</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div id="TransportList" class="col-md-12 margin-bottom-30">
                                                                            <div class="panel panel-primary">
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-striped table-hover">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>Nama Transport</th>
                                                                                                <th>Jarak (KM)</th>
                                                                                                <th>Harga</th>
                                                                                                <th>&nbsp;</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody id="bodyTransport">
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="event" class="tab-pane fade in">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="panel panel-default">
                                                                <div class="panel-body">
                                                                    <div id="AlertEventContract" hidden="true"></div>
                                                                    <?php echo $this->Html->link('Add New', 'javascript:;', ['class' => 'disable btn btn-sm btn-success', 'id' => 'ButtonAddEventContract', 'title' => 'Click to Add', 'escape' => false]); ?>
                                                                    <input type="hidden" id="TotalPriceEventContract" class="form-control input-sm" value="0">
                                                                    <div class="row">
                                                                        <div id="EventAdd" class="col-md-12 margin-bottom-30">
                                                                            <div class="panel panel-primary">
                                                                                <div class="panel-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label>Nama Event</label>
                                                                                                <input type="text" id="EventName" class="form-control input-sm">
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Harga</label>
                                                                                                <input type="text" id="PriceEvent" class="form-control input-sm number">
                                                                                            </div>
                                                                                            <button type="button" class="btn btn-sm btn-success" id="SaveAddEventContract">Simpan</button>
                                                                                            <button type="button" class="btn btn-sm btn-default" id="CancelAddEventContract">Batal</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div id="EventList" class="col-md-12 margin-bottom-30">
                                                                            <div class="panel panel-primary">
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-striped table-hover">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>Nama Event</th>
                                                                                                <th>Harga</th>
                                                                                                <th>&nbsp;</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody id="bodyEvent">
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 margin-bottom-30">
                                    <h4>Data Pasien</h4>
                                    <div class="panel panel-primary">
                                        <div class="panel-body">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama</label>
                                                    <select name="patient_id" id="Patient" class="form-control input-sm">
                                                        <option value="">--Silahkan Pilih--</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Rekomendasi Dari</label>
                                                    <input type="text" id="RekomendasiDari" class="form-control input-sm"readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Jenis Kelamin</label>
                                                    <input type="text" id="JenisKelamin" class="form-control input-sm"readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Umur</label>
                                                    <input type="number" id="Umur" class="form-control input-sm"readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Berat Badan (kg)</label>
                                                    <input type="number" id="BeratBadan" class="form-control input-sm"readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Tinggi Badan (cm)</label>
                                                    <input type="number" id="TinggiBadan" class="form-control input-sm"readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Alamat Lengkap</label>
                                                    <textarea class="form-control input-sm" id="AlamatLengkap" rows="4" readonly></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Alat Yang Terpasang</label>
                                                    <textarea class="form-control input-sm" id="AlatTerpasang" rows="4" readonly></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Diagnosa</label>
                                                    <textarea class="form-control input-sm" id="Diagnosa" rows="4" readonly></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Keluhan Utama</label>
                                                    <textarea class="form-control input-sm" id="KeluhanUtama" rows="4" readonly></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-md-offset-6">
                                    <div class="col-md-12">
                                        <div class="form-group text-right">
                                            <button type="submit" class="btn btn-primary" id="submit_ticket">Simpan</button>
                                            <?php echo $this->Html->link('Kembali', ['controller' => 'Contracts', 'action' => 'index'], ['class' => 'btn btn-danger', 'escape' => false]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
