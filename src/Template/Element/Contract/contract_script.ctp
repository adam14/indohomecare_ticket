<script>
    function selectNurses() {
        $.ajax({
            type: 'GET',
            url: '<?php echo $this->Url->build(['controller' => 'NurseContracts', 'action' => 'getNurse']); ?>',
            dataType: 'json',
            success: function(result) {
                $('#Nurses').empty();
				$('#Nurses').append(new Option('-- Silakan Pilih --', ''));

                for (i = 0; i < result.data.length; i++) {
                    $('#Nurses').append('<option value="'+ result.data[i]['id'] +'" category="'+ result.data[i]['nurse_category_id'] +'">'+ result.data[i]['fullname'] +'</option>');
                }
            }
        });
    }

    function selectNurseCategory() {
        $.ajax({
            type: 'GET',
            url: '<?php echo $this->Url->build(['controller' => 'NurseContracts', 'action' => 'getNurseCategories']); ?>',
            dataType: 'json',
            beforeSend: function() {
                $('#NurseCategory').empty();
                $('#NurseCategory').append(new Option('Loading...', ''));
            },
            success: function(result) {
                $('#NurseCategory').empty();
				$('#NurseCategory').append(new Option('-- Silakan Pilih --', ''));

                for (i = 0; i < result.data.length; i++) {
                    $('#NurseCategory').append('<option value="'+ result.data[i]['id'] +'">'+ result.data[i]['name'] +'</option>');
                }
            }
        })
    }

    function selectTherapist() {
        $.ajax({
            type: 'GET',
            url: '<?php echo $this->Url->build(['controller' => 'TherapistContracts', 'action' => 'getTherapist']); ?>',
            dataType: 'json',
            beforeSend: function() {
                $('#Therapist').empty();
                $('#Therapist').append(new Option('Loading...', ''));
            },
            success: function(result) {
                $('#Therapist').empty();
				$('#Therapist').append(new Option('-- Silakan Pilih --', ''));

                for (i = 0; i < result.data.length; i++) {
                    $('#Therapist').append('<option value="'+ result.data[i]['id'] +'" type-therapist="'+ result.data[i]['therapist_type_id'] +'">'+ result.data[i]['name'] +'</option>');
                }
            }
        });
    }

    function selectTherapistType() {
        $.ajax({
            type: 'GET',
            url: '<?php echo $this->Url->build(['controller' => 'TherapistContracts', 'action' => 'getTherapistType']); ?>',
            dataType: 'json',
            beforeSend: function() {
                $('#TherapistType').empty();
                $('#TherapistType').append(new Option('Loading...', ''));
            },
            success: function(result) {
                $('#TherapistType').empty();
                $('#TherapistType').append(new Option('-- Silakan Pilih --', ''));

                for (i = 0; i < result.data.length; i++) {
                    $('#TherapistType').append('<option value="'+ result.data[i]['id'] +'">'+ result.data[i]['name'] +'</option>');
                }
            }
        });
    }

    function selectMedicTool() {
        $.ajax({
            type: 'GET',
            url: '<?php echo $this->Url->build(['controller' => 'MedicToolContracts', 'action' => 'getMedicTool']); ?>',
            dataType: 'json',
            beforeSend: function() {
                $('#MedicTool').empty();
                $('#MedicTool').append(new Option('Loading...', ''));
            },
            success: function(result) {
                $('#MedicTool').empty();
				$('#MedicTool').append(new Option('-- Silakan Pilih --', ''));

                for (i = 0; i < result.data.length; i++) {
                    $('#MedicTool').append('<option value="'+ result.data[i]['id'] +'">'+ result.data[i]['name'] +'</option>');
                }
            }
        });
    }

    function selectTransport() {
        $.ajax({
            type: 'GET',
            url: '<?php echo $this->Url->build(['controller' => 'ContractTransport', 'action' => 'getTransport']); ?>',
            dataType: 'json',
            beforeSend: function() {
                $('#Transport').empty();
                $('#Transport').append(new Option('Loading...', ''));
            },
            success: function(result) {
                $('#Transport').empty();
				$('#Transport').append(new Option('-- Silakan Pilih --', ''));

                for (i = 0; i < result.data.length; i++) {
                    $('#Transport').append('<option value="'+ result.data[i]['id'] +'">'+ result.data[i]['name'] +' [Rp. '+ formatRupiah(result.data[i]['price']) +']</option>');
                }
            }
        });
    }

    function listNurse(contract_id) {
        var data_nurse_contract = {
            'contract_id' : contract_id
        };
        

        $.ajax({
            type: 'POST',
            url: '<?php echo $this->Url->build(['controller' => 'Contracts', 'action' => 'getAllNurseContract']); ?>',
            data: data_nurse_contract,
            dataType: 'json',
            beforeSend: function() {
                $('#bodyNurse').html('');
            },
            success: function(result) {
                if (result.data.data.length > 0) {
                    var subtotal = 0;
                    var total = 0;

                    for (i = 0; i < result.data.data.length; i++) {
                        subtotal += parseInt(result.data.data[i]['nurse_sessions']['price']);

                        $('#bodyNurse').append(`
                            <tr>
                                <td>`+ result.data.data[i]['nurses']['fullname'] +`</td>
                                <td>`+ result.data.data[i]['nurse_sessions']['name'] +`</td>
                                <td>Rp. `+ formatRupiah(result.data.data[i]['nurse_sessions']['price']) +`</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger" id="ButtonDeleteContractNurse" data-nurse-contract-id="`+ result.data.data[i]['id'] +`"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        `);
                    }

                    $('#bodyNurse').append(`
                        <tr>
                            <td colspan="2" align="right"><b>Subtotal</b></td>
                            <td><b>Rp. `+ formatRupiah(subtotal.toString()) +`</b></td>
                            <td></td>
                        </tr>
                    `);

                    $('#TotalPriceNurseContract').val(subtotal.toString());
                    $('#SubtotalPriceNurseContract').html('Subtotal Perawat : Rp. ' + formatRupiah(subtotal.toString()));
                    TotalPrice();

                    $('#ButtonDeleteContractNurse').on('click', function(e) {
                        var nurse_contract_id = $(this).data('nurse-contract-id');
                        var data_nurse_contract = {
                            'nurse_contract_id' : nurse_contract_id
                        };

                        $.ajax({
                            type: 'POST',
                            url: '<?php echo $this->Url->build(['controller' => 'NurseContracts', 'action' => 'deleteAjax']); ?>',
                            data: data_nurse_contract,
                            dataType: 'json',
                            success: function(result) {
                                if (result.status == 'true') {
                                    $('#AlertNurseContract').html(`<div class="alert alert-success">Data successfully deleted.</div>`);
                                } else {
                                    $('#AlertNurseContract').html(`<div class="alert alert-danger">There was error. Please try again.</div>`);
                                }

                                $('#AlertNurseContract').show('slow');
                                listNurse(contract_id);
                            }
                        });
                    });
                } else {
                    var subtotal = 0;

                    $('#TotalPriceNurseContract').val(subtotal.toString());
                    $('#SubtotalPriceNurseContract').html('');
                    TotalPrice();
                }
            }
        });
    }

    function listTherapist(contract_id) {
        var data_therapist_contract = {
            'contract_id' : contract_id
        };

        $.ajax({
            type: 'POST',
            url: '<?php echo $this->Url->build(['controller' => 'Contracts', 'action' => 'getAllTherapistContract']); ?>',
            data: data_therapist_contract,
            dataType: 'json',
            beforeSend: function() {
                $('#bodyTherapist').html('');
            },
            success: function(result) {
                if (result.data.data.length > 0) {
                    var subtotal = 0;
                    var total = 0;

                    for (i = 0; i < result.data.data.length; i++) {
                        subtotal += parseInt(result.data.data[i]['therapist_sessions']['price']);

                        $('#bodyTherapist').append(`
                            <tr>
                                <td>`+ result.data.data[i]['therapists']['name'] +`</td>
                                <td>`+ result.data.data[i]['therapist_sessions']['name'] +`</td>
                                <td>Rp. `+ formatRupiah(result.data.data[i]['therapist_sessions']['price']) +`</td>
                                <td>
                                    <a href="javascript:;" class="btn btn-sm btn-danger" id="ButtonDeleteContractTherapist" data-therapist-contract-id="`+ result.data.data[i]['id'] +`"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        `);
                    }

                    $('#bodyTherapist').append(`
                        <tr>
                            <td colspan="2" align="right"><b>Subtotal</b></td>
                            <td><b>Rp. `+ formatRupiah(subtotal.toString()) +`</b></td>
                            <td></td>
                        </tr>
                    `);
                    
                    $('#TotalPriceTherapistContract').val(subtotal.toString());
                    $('#SubtotalPriceTherapistContract').html('Subtotal Terapi : Rp. ' + formatRupiah(subtotal.toString()));
                    TotalPrice();

                    $('#ButtonDeleteContractTherapist').on('click', function(e) {
                        var therapist_contract_id = $(this).data('therapist-contract-id');
                        var data_therapist_contract = {
                            'therapist_contract_id' : therapist_contract_id
                        };

                        $.ajax({
                            type: 'POST',
                            url: '<?php echo $this->Url->build(['controller' => 'TherapistContracts', 'action' => 'deleteAjax']); ?>',
                            data: data_therapist_contract,
                            dataType: 'json',
                            success: function(result) {
                                if (result.status == 'true') {
                                    $('#AlertTherapistContract').html(`<div class="alert alert-success">Data successfully deleted.</div>`);
                                } else {
                                    $('#AlertTherapistContract').html(`<div class="alert alert-danger">There was error. Please try again.</div>`);
                                }

                                $('#AlertTherapistContract').show('slow');
                                listTherapist(contract_id);
                            }
                        });
                    });
                } else {
                    var subtotal = 0;

                    $('#TotalPriceTherapistContract').val(subtotal.toString());
                    $('#SubtotalPriceTherapistContract').html('');
                    TotalPrice();
                }
            }
        });
    }

    function listMedicTool(contract_id) {
        var data_medic_tool_contract = {
            'contract_id' : contract_id
        };

        $.ajax({
            type: 'POST',
            url: '<?php echo $this->Url->build(['controller' => 'Contracts', 'action' => 'getAllMedicToolContract']); ?>',
            data: data_medic_tool_contract,
            dataType: 'json',
            beforeSend: function() {
                $('#bodyMedicTool').html('');
            },
            success: function(result) {
                if (result.data.data.length > 0) {
                    var subtotal = 0;
                    var total = 0;

                    for (i = 0; i < result.data.data.length; i++) {
                        subtotal += parseInt(result.data.data[i]['total_price']);

                        $('#bodyMedicTool').append(`
                            <tr>
                                <td>`+ result.data.data[i]['medic_tools']['name'] +`</td>
                                <td>Rp. `+ formatRupiah(result.data.data[i]['medic_tool_sessions']['price']) +`</td>
                                <td>`+ result.data.data[i]['quantity'] +`</td>
                                <td>Rp. `+ formatRupiah(result.data.data[i]['total_price']) +`</td>
                                <td>
                                    <a href="javascript:;" class="btn btn-sm btn-danger" id="ButtonDeleteContractMedicTool" data-medic-tool-contract-id="`+ result.data.data[i]['id'] +`"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        `);
                    }

                    $('#bodyMedicTool').append(`
                        <tr>
                            <td colspan="3" align="right"><b>Subtotal</b></td>
                            <td><b>Rp `+ formatRupiah(subtotal.toString()) +`</b></td>
                            <td>&nbsp;</td>
                        </tr>
                    `);
                    
                    $('#TotalPriceMedicToolContract').val(subtotal.toString());
                    $('#SubtotalPriceMedicToolContract').html('Subtotal Alkes : Rp. ' + formatRupiah(subtotal.toString()));
                    TotalPrice();

                    $('#ButtonDeleteContractMedicTool').on('click', function(e) {
                        var medic_tool_contract_id = $(this).data('medic-tool-contract-id');
                        var data_medic_tool_contract = {
                            'medic_tool_contract_id' : medic_tool_contract_id
                        };

                        $.ajax({
                            type: 'POST',
                            url: '<?php echo $this->Url->build(['controller' => 'MedicToolContracts', 'action' => 'deleteAjax']); ?>',
                            data: data_medic_tool_contract,
                            dataType: 'json',
                            success: function(result) {
                                if (result.status == 'true') {
                                    $('#AlertMedicToolContract').html(`<div class="alert alert-success">Data successfully deleted.</div>`);
                                } else {
                                    $('#AlertMedicToolContract').html(`<div class="alert alert-danger">There was error. Please try again.</div>`);
                                }

                                $('#AlertMedicToolContract').show('slow');
                                listMedicTool(contract_id);
                            }
                        });
                    });
                } else {
                    var subtotal = 0;

                    $('#TotalPriceMedicToolContract').val(subtotal.toString());
                    $('#SubtotalPriceMedicToolContract').html('');
                    TotalPrice();
                }
            }
        });
    }

    function listTransport(contract_id) {
        var data_transport_contract = {
            'contract_id' : contract_id
        };

        $.ajax({
            type: 'POST',
            url: '<?php echo $this->Url->build(['controller' => 'Contracts', 'action' => 'getAllTransportContract']); ?>',
            data: data_transport_contract,
            dataType: 'json',
            beforeSend: function() {
                $('#bodyTransport').html('');
            },
            success: function(result) {
                if (result.data.data.length > 0) {
                    var subtotal = 0;
                    var total = 0;

                    for (i = 0; i < result.data.data.length; i++) {
                        subtotal += parseInt(result.data.data[i]['transport_times']['price']);

                        $('#bodyTransport').append(`
                            <tr>
                                <td>`+ result.data.data[i]['transport_times']['name'] +`</td>
                                <td>`+ result.data.data[i]['distance'] +`</td>
                                <td>Rp. `+ formatRupiah(result.data.data[i]['transport_times']['price']) +`</td>
                                <td>
                                    <a href="javascript:;" class="btn btn-sm btn-danger" id="ButtonDeleteContractTransport" data-transport-contract-id="`+ result.data.data[i]['id'] +`"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        `);
                    }

                    $('#bodyTransport').append(`
                        <tr>
                            <td colspan="2" align="right"><b>Subtotal</b></td>
                            <td><b>Rp `+ formatRupiah(subtotal.toString()) +`</b></td>
                            <td>&nbsp;</td>
                        </tr>
                    `);
                    
                    $('#TotalPriceTransportContract').val(subtotal.toString());
                    $('#SubtotalPriceTransportContract').html('Subtotal Transport : Rp. ' + formatRupiah(subtotal.toString()));
                    TotalPrice();

                    $('#ButtonDeleteContractTransport').on('click', function(e) {
                        var transport_contract_id = $(this).data('transport-contract-id');
                        var data_transport_contract = {
                            'transport_contract_id' : transport_contract_id
                        };

                        $.ajax({
                            type: 'POST',
                            url: '<?php echo $this->Url->build(['controller' => 'ContractTransport', 'action' => 'deleteAjax']); ?>',
                            data: data_transport_contract,
                            dataType: 'json',
                            success: function(result) {
                                if (result.status == 'true') {
                                    $('#AlertTransportContract').html(`<div class="alert alert-success">Data successfully deleted.</div>`);
                                } else {
                                    $('#AlertTransportContract').html(`<div class="alert alert-danger">There was error. Please try again.</div>`);
                                }

                                $('#AlertTransportContract').show('slow');
                                listTransport(contract_id);
                            }
                        });
                    });
                } else {
                    var subtotal = 0;

                    $('#TotalPriceTransportContract').val(subtotal.toString());
                    $('#SubtotalPriceTransportContract').html('');
                    TotalPrice();
                }
            }
        });
    }

    function listEvent(contract_id) {
        var data_event_contract = {
            'contract_id' : contract_id
        };

        $.ajax({
            type: 'POST',
            url: '<?php echo $this->Url->build(['controller' => 'Contracts', 'action' => 'getAllEventContract']); ?>',
            data: data_event_contract,
            dataType: 'json',
            beforeSend: function() {
                $('#bodyEvent').html('');
            },
            success: function(result) {
                if (result.data.data.length > 0) {
                    var subtotal = 0;
                    var total = 0;

                    for (i = 0; i < result.data.data.length; i++) {
                        subtotal += parseInt(result.data.data[i]['price']);

                        $('#bodyEvent').append(`
                            <tr>
                                <td>`+ result.data.data[i]['event_name'] +`</td>
                                <td>Rp. `+ formatRupiah(result.data.data[i]['price']) +`</td>
                                <td>
                                    <a href="javascript:;" class="btn btn-sm btn-danger" id="ButtonDeleteContractEvent" data-event-contract-id="`+ result.data.data[i]['id'] +`"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        `);
                    }

                    $('#bodyEvent').append(`
                        <tr>
                            <td align="right"><b>Subtotal</b></td>
                            <td><b>Rp `+ formatRupiah(subtotal.toString()) +`</b></td>
                            <td>&nbsp;</td>
                        </tr>
                    `);
                    
                    $('#TotalPriceEventContract').val(subtotal.toString());
                    $('#SubtotalPriceEventContract').html('Subtotal Event : Rp. ' + formatRupiah(subtotal.toString()));
                    TotalPrice();

                    $('#ButtonDeleteContractEvent').on('click', function(e) {
                        var event_contract_id = $(this).data('event-contract-id');
                        var data_event_contract = {
                            'event_contract_id' : event_contract_id
                        };

                        $.ajax({
                            type: 'POST',
                            url: '<?php echo $this->Url->build(['controller' => 'ContractEvent', 'action' => 'deleteAjax']); ?>',
                            data: data_event_contract,
                            dataType: 'json',
                            success: function(result) {
                                if (result.status == 'true') {
                                    $('#AlertEventContract').html(`<div class="alert alert-success">Data successfully deleted.</div>`);
                                } else {
                                    $('#AlertEventContract').html(`<div class="alert alert-danger">There was error. Please try again.</div>`);
                                }

                                $('#AlertEventContract').show('slow');
                                listEvent(contract_id);
                            }
                        });
                    });
                } else {
                    var subtotal = 0;

                    $('#TotalPriceEventContract').val(subtotal.toString());
                    $('#SubtotalPriceEventContract').html('');
                    TotalPrice();
                }
            }
        });
    }

    function TotalPrice(contract_id) {
        var subtotal = 0;
        var total = 0;
        var price_nurse_contract = $('#TotalPriceNurseContract').val();
        var price_therapist_contract = $('#TotalPriceTherapistContract').val();
        var price_medic_tool_contract = $('#TotalPriceMedicToolContract').val();
        var price_transport_contract = $('#TotalPriceTransportContract').val();
        var price_event_contract = $('#TotalPriceEventContract').val();

        subtotal += parseInt(price_nurse_contract);
        subtotal += parseInt(price_therapist_contract);
        subtotal += parseInt(price_medic_tool_contract);
        subtotal += parseInt(price_transport_contract);
        subtotal += parseInt(price_event_contract);

        $('#JumlahBiaya').val('Rp. ' +formatRupiah(subtotal.toString()));
        $('#TotalPriceContract').val(subtotal);
    }

    function formatRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }

    $(document).ready(function() {
        var contract_id = '<?php echo (!empty($contracts)) ? $contracts['id'] : ''; ?>';
        TotalPrice();

        $('.menu-tab').on('click', function() {
            var value = $(this).attr('href');

            if (value == '#perawat') {
                $('#AlertNurseContract').hide();
                listNurse(contract_id);
            } else if (value == '#therapist') {
                $('#AlertTherapistContract').hide();
                listTherapist(contract_id);
            } else if (value == '#alkes') {
                $('#AlertMedicToolContract').hide();
                listMedicTool(contract_id);
            } else if (value == '#transport') {
                $('#AlertTransportContract').hide();
                listTransport(contract_id);
            } else if (value == '#event') {
                $('#AlertEventContract').hide();
                listEvent(contract_id);
                TotalPrice();
            }
        });

        /** Nurse */
        listNurse(contract_id);

        $('#NurseAdd').hide();

        $('#ButtonAddContractNurse').on('click', function() {
            $('#NurseAdd').show();

            $('#ButtonAddContractNurse').hide();
            $('#NurseList').hide();
            // selectNurses();
            selectNurseCategory();
        });

        $('#SaveAddContractNurse').on('click', function() {
            var nurse_id = $('#Nurses').val();
            var nurse_session_id = $('#NurseSessions').val();
            var data_nurse_contract = {
                'nurse_id' : nurse_id,
                'nurse_session_id' : nurse_session_id,
                'contract_id' : contract_id
            }

            $.ajax({
                type: 'POST',
                url: '<?php echo $this->Url->build(['controller' => 'NurseContracts', 'action' => 'saveNurseContract']); ?>',
                data: data_nurse_contract,
                dataType: 'json',
                success: function(result) {
                    if (result.status == 'true') {
                        listNurse(contract_id);

                        $('#NurseAdd').hide();
                        $('#ButtonAddContractNurse').show();
                        $('#NurseList').show();

                        $('#NurseCategory').empty();
                        $('#NurseCategory').append(new Option('-- Silakan Pilih --', ''));
                        $('#Nurses').empty();
                        $('#Nurses').append(new Option('-- Silakan Pilih --', ''));
                        $('#NurseSessions').empty();
                        $('#NurseSessions').append(new Option('-- Silakan Pilih --', ''));
                    }
                }
            });
        });

        $('#CancelAddContractNurse').on('click', function() {
            $('#NurseAdd').hide();

            $('#ButtonAddContractNurse').show();
            $('#NurseList').show();

            $('#NurseCategory').empty();
            $('#NurseCategory').append(new Option('-- Silakan Pilih --', ''));
            $('#Nurses').empty();
			$('#Nurses').append(new Option('-- Silakan Pilih --', ''));
            $('#NurseSessions').empty();
			$('#NurseSessions').append(new Option('-- Silakan Pilih --', ''));
        });

        $('#NurseCategory').on('change', function(e) {
            var nurse_category_id = $(this).val();
            var data_nurse = {
                'nurse_category_id' : nurse_category_id
            };

            $.ajax({
                type: 'POST',
                url: '<?php echo $this->Url->build(['controller' => 'NurseContracts', 'action' => 'getNurse']); ?>',
                data: data_nurse,
                dataType: 'json',
                beforeSend: function() {
                    $('#Nurses').empty();
                    $('#Nurses').append(new Option('Loading...', ''));
                },
                success: function(result) {
                    $('#Nurses').empty();
                    $('#Nurses').append(new Option('-- Silakan Pilih --', ''));

                    for (i = 0; i < result.data.length; i++) {
                        $('#Nurses').append('<option value="'+ result.data[i]['id'] +'" category="'+ result.data[i]['nurse_category_id'] +'">'+ result.data[i]['fullname'] +'</option>');
                    }
                }
            });
        });

        $('#Nurses').on('change', function(e) {
			var nurse_id = $(this).val();
			var nurse_category_id = $('#Nurses option:selected').attr("category");
			var data_nurse_session = {
				'nurse_id' : nurse_id,
				'nurse_category_id' : nurse_category_id
			}

			$.ajax({
				type: 'POST',
				url: '<?php echo $this->Url->build(['controller' => 'NurseContracts', 'action' => 'getNurseSessions']) ?>',
				data: data_nurse_session,
				dataType: "json",
                beforeSend: function() {
                    $('#NurseSessions').empty();
                    $('#NurseSessions').append(new Option('Loading...', ''));
                },
				success: function(result) {
					$('#NurseSessions').empty();
					$('#NurseSessions').append(new Option('-- Silakan Pilih --', ''));

					for (i = 0; i < result.data.length; i++) {
						$('#NurseSessions').append('<option value="'+ result.data[i].id +'">'+ result.data[i].name +' [Rp. '+ formatRupiah(result.data[i].price) +']</option>');
					}
				}
			});
		});
        /** End */

        /** Therapist */
        listTherapist(contract_id);

        $('#TherapistAdd').hide();

        $('#ButtonAddTherapistContract').on('click', function() {
            $('#TherapistAdd').show();

            $('#ButtonAddTherapistContract').hide();
            $('#TherapistList').hide();
            // selectTherapist();
            selectTherapistType();
        });

        $('#SaveAddTherapistContract').on('click', function() {
            var therapist_id = $('#Therapist').val();
            var therapist_session_id = $('#TherapistSessions').val();
            var data_therapist_contract = {
                'therapist_id' : therapist_id,
                'therapist_session_id' : therapist_session_id,
                'contract_id' : contract_id
            }

            $.ajax({
                type: 'POST',
                url: '<?php echo $this->Url->build(['controller' => 'TherapistContracts', 'action' => 'saveTherapistContract']); ?>',
                data: data_therapist_contract,
                dataType: 'json',
                success: function(result) {
                    if (result.status == 'true') {
                        listTherapist(contract_id);

                        $('#TherapistAdd').hide();
                        $('#ButtonAddTherapistContract').show();
                        $('#TherapistList').show();

                        $('#TherapistType').empty();
                        $('#TherapistType').append(new Option('-- Silakan Pilih --', ''));
                        $('#Therapist').empty();
                        $('#Therapist').append(new Option('-- Silakan Pilih --', ''));
                        $('#TherapistSessions').empty();
                        $('#TherapistSessions').append(new Option('-- Silakan Pilih --', ''));
                    }
                }
            });
        });

        $('#CancelAddTherapistContract').on('click', function() {
            $('#TherapistAdd').hide();

            $('#ButtonAddTherapistContract').show();
            $('#TherapistList').show();

            $('#TherapistType').empty();
            $('#TherapistType').append(new Option('-- Silakan Pilih --', ''));
            $('#Therapist').empty();
			$('#Therapist').append(new Option('-- Silakan Pilih --', ''));
            $('#TherapistSessions').empty();
			$('#TherapistSessions').append(new Option('-- Silakan Pilih --', ''));
        });

        $('#TherapistType').on('change', function(e) {
            var therapist_type_id = $(this).val();
            var data_therapist = {
                'therapist_type_id' : therapist_type_id
            };

            $.ajax({
                type: 'POST',
                url: '<?php echo $this->Url->build(['controller' => 'TherapistContracts', 'action' => 'getTherapist']); ?>',
                data: data_therapist,
                dataType: 'json',
                beforeSend: function() {
                    $('#Therapist').empty();
                    $('#Therapist').append(new Option('Loading...', ''));
                },
                success: function(result) {
                    $('#Therapist').empty();
                    $('#Therapist').append(new Option('-- Silakan Pilih --', ''));

                    for (i = 0; i < result.data.length; i++) {
                        $('#Therapist').append('<option value="'+ result.data[i]['id'] +'" type-therapist="'+ result.data[i]['therapist_type_id'] +'">'+ result.data[i]['name'] +'</option>');
                    }
                }
            })
        });

        $('#Therapist').on('change', function(e) {
			var therapist_id = $(this).val();
			var therapist_type_id = $('#Therapist option:selected').attr("type-therapist");
			var data_therapist_session = {
				'therapist_id' : therapist_id,
				'therapist_type_id' : therapist_type_id
			}

			$.ajax({
				type: 'POST',
				url: '<?php echo $this->Url->build(['controller' => 'TherapistContracts', 'action' => 'getTherapistSessions']) ?>',
				data: data_therapist_session,
				dataType: "json",
                beforeSend: function() {
                    $('#TherapistSessions').empty();
                    $('#TherapistSessions').append(new Option('Loading...', ''));
                },
				success: function(result) {
					$('#TherapistSessions').empty();
					$('#TherapistSessions').append(new Option('-- Silakan Pilih --', ''));

                    console.log(result.data.length);

					for (i = 0; i < result.data.length; i++) {
						$('#TherapistSessions').append('<option value="'+ result.data[i].id +'">'+ result.data[i].name +' [Rp. '+ formatRupiah(result.data[i].price) +']</option>');
					}
				}
			});
		});
        /** End */

        /** Medic Tools */
        listMedicTool(contract_id);

        $('#MedicToolAdd').hide();

        $('#ButtonAddMedicToolContract').on('click', function() {
            $('#MedicToolAdd').show();

            $('#ButtonAddMedicToolContract').hide();
            $('#MedicToolList').hide();
            selectMedicTool();
        });

        $('#SaveAddMedicToolContract').on('click', function() {
            var medic_tool_id = $('#MedicTool').val();
            var medic_tool_session_id = $('#MedicToolSessions').val();
            var quantity = $('#Quantity').val();
            var total_price = $('#SaveTotalPriceMedicToolContract').val();
            var data_medic_tool_contract = {
                'medic_tool_id' : medic_tool_id,
                'medic_tool_session_id' : medic_tool_session_id,
                'quantity' : quantity,
                'total_price' : total_price,
                'contract_id' : contract_id
            }

            $.ajax({
                type: 'POST',
                url: '<?php echo $this->Url->build(['controller' => 'MedicToolContracts', 'action' => 'saveMedicToolContract']); ?>',
                data: data_medic_tool_contract,
                dataType: 'json',
                success: function(result) {
                    if (result.status == 'true') {
                        listMedicTool(contract_id);

                        $('#MedicToolAdd').hide();
                        $('#ButtonAddMedicToolContract').show();
                        $('#MedicToolList').show();

                        $('#MedicTool').empty();
                        $('#MedicTool').append(new Option('-- Silakan Pilih --', ''));
                        $('#MedicToolSessions').empty();
                        $('#MedicToolSessions').append(new Option('-- Silakan Pilih --', ''));
                        $('#Quantity').val('');
                        $('#FormTotalPriceMedicToolContract').val('');
                        $('#PriceMedicToolSessions').val('');
                        $('#SaveTotalPriceMedicToolContract').val('');
                    }
                }
            });
        });

        $('#CancelAddMedicToolContract').on('click', function() {
            $('#MedicToolAdd').hide();

            $('#ButtonAddMedicToolContract').show();
            $('#MedicToolList').show();

            $('#MedicTool').empty();
			$('#MedicTool').append(new Option('-- Silakan Pilih --', ''));
            $('#MedicToolSessions').empty();
			$('#MedicToolSessions').append(new Option('-- Silakan Pilih --', ''));
            $('#Quantity').val('');
            $('#FormTotalPriceMedicToolContract').val('');
            $('#PriceMedicToolSessions').val('');
            $('#SaveTotalPriceMedicToolContract').val('');
        });

        $('#MedicTool').on('change', function(e) {
			var medic_tool_id = $(this).val();
			var data_medic_tool_session = {
				'medic_tool_id' : medic_tool_id
			}

			$.ajax({
				type: 'POST',
				url: '<?php echo $this->Url->build(['controller' => 'MedicToolContracts', 'action' => 'getMedicToolSessions']) ?>',
				data: data_medic_tool_session,
				dataType: "json",
                beforeSend: function() {
                    $('#MedicToolSessions').empty();
					$('#MedicToolSessions').append(new Option('Loading...', ''));
                },
				success: function(result) {
					$('#MedicToolSessions').empty();
					$('#MedicToolSessions').append(new Option('-- Silakan Pilih --', ''));

					for (i = 0; i < result.data.length; i++) {
						$('#MedicToolSessions').append('<option value="'+ result.data[i].id +'" price-medic-tool-session="'+ result.data[i].price +'">Rp. '+ formatRupiah(result.data[i].price) +'</option>');
					}
				}
			});
		});

        $('#MedicToolSessions').on('change', function(e) {
            $('#PriceMedicToolSessions').val($('#MedicToolSessions option:selected').attr('price-medic-tool-session'));
        });

        $('#Quantity').on('keyup', function(e) {
            var total = $(this).val() * $('#PriceMedicToolSessions').val();

            $('#FormTotalPriceMedicToolContract').val('Rp. ' + formatRupiah(total.toString()));
            $('#SaveTotalPriceMedicToolContract').val(total.toString());
        })
        /** End */

        /** Transport */
        listTransport(contract_id);

        $('#TransportAdd').hide();

        $('#ButtonAddTransportContract').on('click', function() {
            $('#TransportAdd').show();

            $('#ButtonAddTransportContract').hide();
            $('#TransportList').hide();
            selectTransport();
        });

        $('#SaveAddTransportContract').on('click', function() {
            var transport_time_id = $('#Transport').val();
            var distance = $('#Distance').val();
            var data_transport_contract = {
                'transport_time_id' : transport_time_id,
                'distance' : distance,
                'contract_id' : contract_id
            }

            $.ajax({
                type: 'POST',
                url: '<?php echo $this->Url->build(['controller' => 'ContractTransport', 'action' => 'saveTransportContract']); ?>',
                data: data_transport_contract,
                dataType: 'json',
                success: function(result) {
                    if (result.status == 'true') {
                        listTransport(contract_id);

                        $('#TransportAdd').hide();
                        $('#ButtonAddTransportContract').show();
                        $('#TransportList').show();

                        $('#Transport').empty();
                        $('#Transport').append(new Option('-- Silakan Pilih --', ''));
                        $('#Distance').val('');
                    }
                }
            });
        });

        $('#CancelAddTransportContract').on('click', function() {
            $('#TransportAdd').hide();

            $('#ButtonAddTransportContract').show();
            $('#TransportList').show();

            $('#Transport').empty();
			$('#Transport').append(new Option('-- Silakan Pilih --', ''));
            $('#Distance').val('');
        });
        /** End */

        /** Event */
        listEvent(contract_id);

        $('#EventAdd').hide();

        $('#ButtonAddEventContract').on('click', function() {
            $('#EventAdd').show();

            $('#ButtonAddEventContract').hide();
            $('#EventList').hide();
        });

        $('#SaveAddEventContract').on('click', function() {
            var event_name = $('#EventName').val();
            var price = $('#PriceEvent').val();
            var data_event_contract = {
                'event_name' : event_name,
                'price' : price,
                'contract_id' : contract_id
            }

            $.ajax({
                type: 'POST',
                url: '<?php echo $this->Url->build(['controller' => 'ContractEvent', 'action' => 'saveEventContract']); ?>',
                data: data_event_contract,
                dataType: 'json',
                success: function(result) {
                    if (result.status == 'true') {
                        listEvent(contract_id);

                        $('#EventAdd').hide();
                        $('#ButtonAddEventContract').show();
                        $('#EventList').show();

                        $('#EventName').val('');
                        $('#PriceEvent').val('');
                    }
                }
            });
        });

        $('#CancelAddEventContract').on('click', function() {
            $('#EventAdd').hide();

            $('#ButtonAddEventContract').show();
            $('#EventList').show();

            $('#EventName').val('');
            $('#PriceEvent').val('');
        });
        /** End */
    });
</script>