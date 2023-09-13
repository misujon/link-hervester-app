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
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="antialiased">

        {{-- Navbar --}}
        <section class="container-wrapper">
            <nav class="navbar bg-dark navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
                <div class="container">
                  <a class="navbar-brand" href="{{ route('home') }}">Link Harvester App</a>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                      <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('home') }}">Home</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link active" href="{{ route('addUrl') }}">Add Urls</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </nav>
        </section>

        {{-- Url Adding process --}}
        <section class="container mt-5">
            <h3>Add Urls</h3>
            <div x-data="{ formData: { urls: '' }, responseMessage: '' }">
                <form @submit.prevent="submitForm(formData)">
                    <div class="mb-3">
                        <textarea class="form-control" rows="15" name="urls" x-model="formData.urls" placeholder="Enter URLs, Example: https://example.com/"></textarea>
                        <small>Each line will be regarded as a single URL...</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Save <i class="bi bi-save-fill"></i></button>
                </form>
            
                <div x-show="responseMessage">
                    <p x-text="responseMessage"></p>
                </div>
            </div>
        </section>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
        <script>
            function submitForm(formData) 
            {
                let reqUrl = "{{ route('saveUrls') }}";
                let token = "{{ csrf_token() }}";

                fetch(reqUrl, {
                    method: 'POST',
                    body: JSON.stringify(formData),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token, // Assuming you have a hidden input with the CSRF token
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Handle the success response here
                    if (data.success) {
                        // Clear the form and show a success message
                        formData.urls = '';
                        Alpine.store('responseMessage', 'Data saved successfully.');
                    }
                })
                .catch(error => {
                    // Handle fetch or server-side errors here
                    Alpine.store('responseMessage', 'Error: ' + error.message);
                });
            }

            document.addEventListener('alpine:init', () => {
                Alpine.store('responseMessage', '');
            });
        </script>
    </body>
</html>
