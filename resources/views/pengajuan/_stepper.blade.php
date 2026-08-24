@php
  $steps = [1 => 'Jalur & Skema', 2 => 'Tim & Unit Kerja', 3 => 'Proposal & Biaya', 4 => 'Rencana Luaran', 5 => 'Review & Kirim'];
@endphp
<div class="stepper">
  @foreach ($steps as $num => $label)
    @php $state = $num < $current ? 'done' : ($num === $current ? 'active' : ''); @endphp
    <div class="step {{ $state }}">
      <div class="circle">{{ $num < $current ? '✓' : $num }}</div>
      <div class="label">{{ $label }}</div>
    </div>
    @if ($num < 5)
      <div class="line {{ $num < $current ? 'done' : '' }}"></div>
    @endif
  @endforeach
</div>
