<x-app-layout>
    <x-slot name="header">
        <h2 class="font-weight-bold h4 text-dark mb-0">
            Autószerviz
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <div class="mb-4">
                    <form id="search-form" data-url="{{ route("clients.index") }}">
                        <div class="form-group">
                            <label for="client_name">Ügyfél neve</label>
                            <input type="text" id="client_name" class="form-control" placeholder="Ügyfél neve">
                        </div>
                        <div class="form-group mt-3">
                            <label for="card_number">Ügyfél okmányazonosítója</label>
                            <input type="text" id="card_number" class="form-control" placeholder="Okmányazonosító">
                        </div>
                        <button type="submit" id="search-btn" class="btn btn-primary mt-3">Keresés</button>
                    </form>
                    <div id="error-message" class="alert alert-danger mt-3 d-none"></div>
                </div>

                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Ügyfél azonosító</th>
                            <th>Ügyfél neve</th>
                            <th>Okmányazonosító</th>
                            <th class="d-none show-only-success">Autók száma</th>
                            <th class="d-none show-only-success">Szervíznaplók száma</th>
                        </tr>
                    </thead>
                    <tbody id="clients-table">
                    </tbody>
                </table>

                <div id="client-cars-container" class="mt-5 d-none">
                    <h3 class="mb-3">Ügyfél autói</h3>
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Autó sorszáma</th>
                                <th>Autó típusa</th>
                                <th>Regisztrálás időpontja</th>
                                <th>Saját márkás-e</th>
                                <th>Balesetek száma</th>
                                <th>Utolsó esemény</th>
                                <th>Utolsó esemény időpontja</th>
                            </tr>
                        </thead>
                        <tbody id="client-cars-table">
                        </tbody>
                    </table>
                </div>

                <div id="car-service-log-container" class="mt-5 d-none">
                    <h3 class="mb-3">Szerviznapló</h3>
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Alkalom sorszáma</th>
                                <th>Esemény neve</th>
                                <th>Esemény időpontja</th>
                                <th>Munkalap azonosító</th>
                            </tr>
                        </thead>
                        <tbody id="car-service-log-table">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/carservice.js') }}"></script>
</x-app-layout>