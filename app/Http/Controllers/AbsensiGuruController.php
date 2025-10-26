<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\Guru;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use App\Exports\AbsensiGuruExport;

class AbsensiGuruController extends Controller
{
	public function index(Request $request)
	{
		$absensi = AbsensiGuru::with('guru')->orderBy('tanggal','desc')->get();
		return view('absensi.guru', compact('absensi'));
	}

	public function create()
	{
		$gurus = Guru::orderBy('nama')->get();
		return view('absensi.guru_create', compact('gurus'));
	}

	public function store(Request $request)
	{
		$request->validate([
			'guru_id' => 'required|exists:guru,id',
			'tanggal' => 'required|date',
			'jam_masuk' => 'nullable',
			'jam_pulang' => 'nullable',
			'status' => 'required|in:hadir,izin,sakit,alpha',
			'keterangan' => 'nullable',
		]);

		AbsensiGuru::create([
			'guru_id' => $request->guru_id,
			'tanggal' => $request->tanggal,
			'jam_masuk' => $request->jam_masuk,
			'jam_pulang' => $request->jam_pulang,
			'status' => $request->status,
			'keterangan' => $request->keterangan,
		]);

	return redirect()->route('absensi_guru.index')->with('success','Absensi guru berhasil disimpan.');
	}

	public function show(AbsensiGuru $absensiGuru)
	{
		return view('absensi.guru_show', ['absensi' => $absensiGuru]);
	}

	public function edit(AbsensiGuru $absensiGuru)
	{
		$gurus = Guru::orderBy('nama')->get();
		return view('absensi.guru_edit', ['absensi' => $absensiGuru, 'gurus' => $gurus]);
	}

	public function update(Request $request, AbsensiGuru $absensiGuru)
	{
		$request->validate([
			'guru_id' => 'required|exists:guru,id',
			'tanggal' => 'required|date',
			'status' => 'required|in:hadir,izin,sakit,alpha',
			'keterangan' => 'nullable',
		]);

		$absensiGuru->update($request->only(['guru_id','tanggal','jam_masuk','jam_pulang','status','keterangan']));
	return redirect()->route('absensi_guru.index')->with('success','Absensi guru berhasil diupdate.');
	}

	public function destroy(AbsensiGuru $absensiGuru)
	{
		$absensiGuru->delete();
	return redirect()->route('absensi_guru.index')->with('success','Absensi guru berhasil dihapus.');
	}

	/**
	 * Export absensi guru to excel or pdf
	 */
	public function export(Request $request, $type)
	{
		$query = AbsensiGuru::with('guru')->orderBy('tanggal','desc');

		if ($request->filled('tanggal')) {
			$query->where('tanggal', $request->tanggal);
		}
		if ($request->filled('periode')) {
			// periode in format YYYY-MM
			$periode = $request->periode;
			[$y, $m] = explode('-', $periode);
			$query->whereYear('tanggal', $y)->whereMonth('tanggal', $m);
		}

		$items = $query->get();

		if ($type === 'excel') {
			return Excel::download(new AbsensiGuruExport($items), 'absensi_guru.xlsx');
		} elseif ($type === 'pdf') {
			$pdf = PDF::loadView('absensi.export_pdf_guru', ['absensi' => $items]);
			return $pdf->download('absensi_guru.pdf');
		}
		return back();
	}
}

