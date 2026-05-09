<td class="p-3">
    @if($inv->status == 'paid')
        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase 
            {{ $inv->payment_method == 'qris' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700' }}">
            {{ $inv->payment_method }}
        </span>
    @else
        <span class="text-red-500 font-bold italic">Belum Bayar</span>
    @endif
</td>