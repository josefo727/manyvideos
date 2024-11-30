<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoFormRequest;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class VideoController extends Controller
{
    public function index(): Response
    {
        $videos = auth()->user()->videos()->with('comments')->paginate();

        return Inertia::render('Admin/Videos/Index', [
            'videos' => $videos,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Videos/Create');
    }

    public function store(VideoFormRequest $request): RedirectResponse
    {
        try {
            $path = $request->file('video')->store('videos', 'public');
            Video::query()->create([
                'name' => $request->input('name'),
                'path' => $path,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('videos.index')->with('success', 'The video is being processed, we will notify you when it is published.');
        } catch (\Exception $e) {
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()
                ->back()
                ->withInput($request->only('name'))
                ->withErrors(['video' => 'There was an error uploading the video: ' . $e->getMessage()]);
        }
    }

    public function edit(Video $video): Response
    {
        if (! Gate::allows('update', $video)) {
            abort(403);
        }

        return Inertia::render('Admin/Videos/Edit', [
            'video' => $video,
        ]);
    }

    public function update(VideoFormRequest $request, Video $video): RedirectResponse
    {
        if (! Gate::allows('update', $video)) {
            abort(403);
        }

        try {
            if ($request->hasFile('video')) {
                Storage::disk('public')->delete($video->path);

                $path = $request->file('video')->store('videos', 'public');
                $video->path = $path;
            }

            $video->name = $request->input('name');
            $video->save();

            return redirect()->route('videos.index')
                ->with('success', 'The video has been updated successfully.');
        } catch (\Exception $e) {
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()
                ->back()
                ->withInput($request->only('name'))
                ->withErrors(['video' => 'There was an error updating the video: ' . $e->getMessage()]);
        }
    }

    public function destroy(Video $video): RedirectResponse
    {
        if (!Gate::allows('delete', $video)) {
            abort(403);
        }

        try {
            Storage::disk('public')->delete($video->path);
            Storage::disk('public')->delete($video->thumbnail);

            $video->delete();

            return redirect()->route('videos.index')
                ->with('success', 'The video has been successfully deleted.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['video' => 'There was an error deleting the video: ' . $e->getMessage()]);
        }
    }
}
