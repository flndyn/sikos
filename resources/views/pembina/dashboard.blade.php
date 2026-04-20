<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pembina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="mb-2">Dashboard Pembina</h3>
                <p class="text-muted mb-4">Anda login sebagai pembina organisasi.</p>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
