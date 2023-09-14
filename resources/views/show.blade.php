<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="antialiased">

        {{-- Navbar --}}
        <section class="container-wrapper">
            <nav class="navbar bg-dark navbar-expand-lg bg-body-tertiary"  data-bs-theme="dark">
                <div class="container">
                  <a class="navbar-brand" href="{{ route('home') }}">Link Harvester App</a>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                      <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('home') }}">Home</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="{{ route('addUrl') }}">Add Urls</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </nav>
        </section>

        {{-- Domains Container Table --}}
        <section class="container mt-5">
            <h3 class="mb-5">Links of domain - {{ $domain->domain_name }}</h3>
            <table class="table table-bordered" id="domains-table">
                <thead class="table-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Url</th>
                    <th scope="col">Created At</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
        </section>
        
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script>
            jQuery("#domains-table").DataTable({
                ajax: '{{ $dataUrl }}',
                processing: true,
                serverSide: true,
                pagingType: "full_numbers",
                pageLength: 10,
                lengthMenu: [
                    [5, 10, 15, 20, 50, 100],
                    [5, 10, 15, 20, 50, 100],
                ],
                order: [[0, 'desc']],
                autoWidth: !1,
                responsive: !0,
                columns: [
                    { data: 'id' },
                    { data: 'url' },
                    { data: 'created_at' },
                ],
                language: {
                    "paginate": {
                        "first": '<button class="btn btn-sm btn-outline-primary mx-1">First</button>',
                        "previous": '<button class="btn btn-sm btn-outline-primary mx-1">Prev</button>',
                        "next": '<button class="btn btn-sm btn-outline-primary mx-1">Next</button>',
                        "last": '<button class="btn btn-sm btn-outline-primary mx-1">Last</button>',
                    }
                }
            });
        </script>
    </body>
</html>
