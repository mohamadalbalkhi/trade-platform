<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trade History</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100">

<div class="min-h-screen">

<header class="bg-slate-900 text-white px-6 py-4 shadow">
<div class="flex justify-between items-center">

<h1 class="text-2xl font-bold">Trade Platform</h1>

<div class="flex gap-3">
<a href="/trade" class="bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-lg">Trade</a>
<a href="/wallet" class="bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-lg">Wallet</a>
<a href="/withdraw" class="bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-lg">Withdraw</a>
</div>

</div>
</header>

<main class="p-6">

<div class="mb-8">
<h2 class="text-4xl font-bold text-slate-800">Trade History</h2>
<p class="text-slate-500 mt-2">All your trading activity</p>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">

<table class="w-full">

<thead class="bg-slate-100">
<tr>

<th class="p-4 text-left text-slate-600">Pair</th>
<th class="p-4 text-left text-slate-600">Type</th>
<th class="p-4 text-left text-slate-600">Amount</th>
<th class="p-4 text-left text-slate-600">Price</th>
<th class="p-4 text-left text-slate-600">Date</th>

</tr>
</thead>

<tbody>

@forelse($trades as $trade)

<tr class="border-t">

<td class="p-4 font-semibold">
{{ $trade->pair }}
</td>

<td class="p-4">

@if($trade->type == 'BUY')

<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
BUY
</span>

@else

<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">
SELL
</span>

@endif

</td>

<td class="p-4">
{{ $trade->amount }} BTC
</td>

<td class="p-4">
${{ $trade->price }}
</td>

<td class="p-4 text-slate-500">
{{ $trade->created_at }}
</td>

</tr>

@empty

<tr>
<td colspan="5" class="text-center p-6 text-slate-500">
No trades yet
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

</main>

</div>

</body>
</html>