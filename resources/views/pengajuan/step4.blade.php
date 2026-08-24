@extends('layouts.app')

@section('title', 'Pengajuan Baru')
@section('crumbs', 'Menu Dosen / Pengajuan Baru')

@section('content')
<link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

<div class="card wizard-card">
  @include('pengajuan._stepper', ['current' => 4])

  <form method="POST" action="{{ route('pengajuan.step4.post') }}">
    @csrf

    @if ($luaranWajib)
      <div class="field">
        <label>Luaran Wajib</label>
        <div class="luaran-item">
          <label style="cursor:default;"><span class="tag-wajib">Wajib</span> {{ $luaranWajib->nama }}</label>
          @if ($luaranWajib->opsi)
            <select name="luaran_wajib_opsi">
              <option value="">Pilih kategori...</option>
              @foreach ($luaranWajib->opsi as $opsi)
                <option value="{{ $opsi }}" {{ $w['luaran_wajib_opsi'] === $opsi ? 'selected' : '' }}>{{ $opsi }}</option>
              @endforeach
            </select>
          @endif
        </div>
      </div>
    @endif

    <div class="field">
      <label>Luaran Tambahan (opsional)</label>
      <div class="check-list" style="max-height:none;">
        @foreach ($luaranTambahan as $l)
          @php $checked = array_key_exists($l->id, $w['luaran_tambahan']); @endphp
          <div class="luaran-item" style="padding:10px 14px;">
            <label>
              <input type="checkbox" name="luaran_check_{{ $l->id }}" onchange="toggleLuaran({{ $l->id }}, this)" {{ $checked ? 'checked' : '' }}>
              {{ $l->nama }}
            </label>
            @if ($l->opsi)
              <select name="luaran_tambahan[{{ $l->id }}]" id="opsi_{{ $l->id }}" {{ $checked ? '' : 'disabled' }}>
                <option value="">Pilih...</option>
                @foreach ($l->opsi as $opsi)
                  <option value="{{ $opsi }}" {{ ($w['luaran_tambahan'][$l->id] ?? null) === $opsi ? 'selected' : '' }}>{{ $opsi }}</option>
                @endforeach
              </select>
            @else
              <input type="hidden" name="luaran_tambahan[{{ $l->id }}]" id="opsi_{{ $l->id }}" value="1" {{ $checked ? '' : 'disabled' }}>
            @endif
          </div>
        @endforeach
      </div>
    </div>

    @if ($w['jenis'] === 'penelitian')
      <div class="field">
        <label>Inovasi Produk</label>
        <textarea name="inovasi_produk" rows="3" placeholder="Uraikan singkat rencana inovasi produk (dalam bentuk teks)...">{{ $w['inovasi_produk'] }}</textarea>
      </div>
    @endif

    <div style="display:flex; justify-content:space-between; margin-top:20px;">
      <a href="{{ route('pengajuan.step3') }}" class="btn btn-outline">Kembali</a>
      <button class="btn btn-primary" type="submit">Selanjutnya</button>
    </div>
  </form>
</div>

<script>
function toggleLuaran(id, checkbox){
  const field = document.getElementById('opsi_' + id);
  field.disabled = !checkbox.checked;
  if (!checkbox.checked) field.value = field.tagName === 'SELECT' ? '' : '';
  else if (field.tagName !== 'SELECT') field.value = '1';
}
</script>
@endsection
