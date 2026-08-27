<?php

namespace App\Http\Controllers;

use App\Models\MediaGallery;
use App\Models\SavedMedia;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = MediaGallery::with(['event', 'uploader']);

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('department')) {
            $query->where('department', $request->input('department'));
        }

        if ($request->filled('year')) {
            $query->where('year', $request->input('year'));
        }

        if ($request->filled('type')) {
            $query->where('media_type', $request->input('type'));
        }

        $mediaItems = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        $categories = Category::all();
        $departments = MediaGallery::whereNotNull('department')->distinct()->pluck('department');
        $years = MediaGallery::distinct()->pluck('year');

        $savedMediaIds = [];
        if (Auth::check()) {
            $savedMediaIds = SavedMedia::where('user_id', Auth::id())->pluck('media_id')->toArray();
        }

        return view('gallery.index', compact('mediaItems', 'categories', 'departments', 'years', 'savedMediaIds'));
    }

    public function upload(Request $request)
    {
        $user = Auth::user();
        if (!$user->isOrganizer() && !$user->isAdmin()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'media_type' => 'required|in:image,video',
            'category' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'year' => 'required|integer|min:2000|max:2099',
            'event_id' => 'nullable|exists:events,id',
            'file' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi|max:20480',
        ]);

        $filePath = '';
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/gallery'), $filename);
            $filePath = 'uploads/gallery/' . $filename;
        }

        MediaGallery::create([
            'event_id' => $validated['event_id'] ?? null,
            'title' => $validated['title'],
            'media_type' => $validated['media_type'],
            'file_path' => $filePath,
            'category' => $validated['category'],
            'department' => $validated['department'],
            'year' => $validated['year'],
            'uploaded_by' => $user->id,
        ]);

        return back()->with('success', 'Media uploaded to gallery successfully.');
    }

    public function toggleFavorite($mediaId)
    {
        if (!Auth::check()) {
            return back()->with('warning', 'Please log in to save media.');
        }

        $userId = Auth::id();
        $saved = SavedMedia::where('user_id', $userId)->where('media_id', $mediaId)->first();

        if ($saved) {
            $saved->delete();
            return back()->with('success', 'Media removed from your saved collection.');
        } else {
            SavedMedia::create([
                'user_id' => $userId,
                'media_id' => $mediaId,
            ]);
            return back()->with('success', 'Media saved to your collection.');
        }
    }
}
