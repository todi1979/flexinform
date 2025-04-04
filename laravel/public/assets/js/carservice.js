$(document).ready(function () {
    $('#search-form').submit(function (e) {
        e.preventDefault();

        // keresési eredmények törlése
        $('#client-cars-table').html('');
        $('#client-cars-container').addClass('d-none');
        $('#car-service-log-table').html('');
        $('#car-service-log-container').addClass('d-none');

        let clientName = $('#client_name').val();
        let cardNumber = $('#card_number').val();
        let errorMessage = '';

        // validáció a keresési feltételekre
        if (!clientName && !cardNumber) {
            errorMessage = 'Kérem, töltse ki az ügyfél nevét vagy okmányazonosítóját!';
        } else if (clientName && cardNumber) {
            errorMessage = 'Csak az egyik mezőt töltse ki!';
        } else if (cardNumber && !/^[a-zA-Z0-9]+$/.test(cardNumber)) {
            errorMessage = 'Az okmányazonosító csak betűkből és számokból állhat!';
        }

        // hiba megjelenítése, ha van
        if (errorMessage) {
            $('#error-message').text(errorMessage).removeClass('d-none');
            return;
        } else {
            $('#error-message').text('').addClass('d-none');
        }

        loadClients(clientName, cardNumber);
    });

    // Ügyfelek betöltése
    function loadClients(clientName = '', cardNumber = '') {
        // Eredmények törlése, hibaüzenet elrejtése
        $('#clients-table').html('');
        $('#client-details').html('');
        $('#error-message').addClass('d-none');
        $('.show-only-success').addClass('d-none');

        $.ajax({
            url: $('#search-form').data('url'),
            method: 'GET',
            data: { name: clientName, card_number: cardNumber },
            success: function (response) {
                let rows = '';
                if (response.length === 0) {
                    // Nincs találat
                    rows = `<tr><td colspan="3" class="text-center">Nincs találat</td></tr>`;
                } else {
                    // Találatok feldolgozása
                    response.forEach(client => {
                        if (response.length === 1) {
                            // sikeres találat esetén részletesebb sor jelenik meg
                            rows += `<tr>
                                        <td>${client.id}</td>
                                        <td><a href="javascript:void(0)" class="client-name" data-id="${client.id}">${client.name}</a></td>
                                        <td>${client.card_number}</td>
                                        <td>${client.car_count}</td>
                                        <td>${client.service_log_count}</td>
                                    </tr>`;
                            $('.show-only-success').removeClass('d-none');
                        } else {
                            // ügyfél lista esetén kevésbé részletes sorok jellenek meg
                            rows += `<tr>
                                        <td>${client.id}</td>
                                        <td><a href="javascript:void(0)" class="client-name" data-id="${client.id}">${client.name}</a></td>
                                        <td>${client.card_number}</td>
                                    </tr>`;
                        }
                    });

                }
                $('#clients-table').html(rows);
            },
            error: function (xhr) {
                // validációs hiba esetén hibaüzenet megjelenítése
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    let errorMessages = new Set();
                    Object.values(errors).forEach(msgArray => {
                        msgArray.forEach(msg => errorMessages.add(msg));
                    });

                    $('#error-message').html(Array.from(errorMessages).join('<br>')).removeClass('d-none');
                }
            }
        });
    }

    // Oldalbetöltéskor automatikusan betölt az ügyfelek listáját
    loadClients();

    // Ügyfél nevére kattintva betölti az autóit
    $(document).on('click', '.client-name', function (e) {
        e.preventDefault();
        let clientId = $(this).data('id');

        // Korábbi eredmények törlése
        $('#client-cars-table').html('');
        $('#car-service-log-table').html('');
        $('#car-service-log-container').addClass('d-none');

        $.ajax({
            url: `/clients/${clientId}/cars`,
            method: 'GET',
            success: function (cars) {
                let carRows = '';
                if (cars.length === 0) {
                    carRows = `<tr><td colspan="8" class="text-center">Nincs találat</td></tr>`;
                } else {
                    cars.forEach(car => {
                        carRows += `<tr>
                                    <td><a href="javascript:void(0);" class="car-service-log" data-client-id="${clientId}" data-car-id="${car.car_id}">${car.car_id}</a></td>
                                    <td>${car.type}</td>
                                    <td>${car.registered}</td>
                                    <td>${car.ownbrand}</td>
                                    <td>${car.accidents}</td>
                                    <td>${car.latest_event}</td>
                                    <td>${car.latest_event_time}</td>
                                </tr>`;
                    });
                }
                $('#client-cars-table').html(carRows);
                $('#client-cars-container').removeClass('d-none');
            }
        });
    });

    // Autó azonosítóra kattintva betölti a szerviznaplót
    $(document).on('click', '.car-service-log', function (e) {
        e.preventDefault();
        let carId = $(this).data('car-id');
        let clientId = $(this).data('client-id');
        $('#car-service-log-table').html('');

        $.ajax({
            url: `/cars/${clientId}/${carId}/service-log`,
            method: 'GET',
            success: function (logs) {
                let logRows = '';
                if (logs.length === 0) {
                    logRows = `<tr><td colspan="4" class="text-center">Nincs találat</td></tr>`;
                } else {
                    logs.forEach(log => {
                        let eventTime = log.event === 'regisztralt' ? log.registration_time : log.event_time;
                        logRows += `<tr>
                                    <td>${log.log_number}</td>
                                    <td>${log.event}</td>
                                    <td>${eventTime}</td>
                                    <td>${log.document_id}</td>
                                </tr>`;
                    });
                }
                $('#car-service-log-table').html(logRows);
                $('#car-service-log-container').removeClass('d-none');
            }
        });
    });
});