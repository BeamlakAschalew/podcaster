<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use App\Models\PodcastFile;
use App\Models\PodcastScript;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PodcastController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): Response
    {
        return Inertia::render('podcasts/Index', [
            'podcasts' => Podcast::where('user_id', $request->user())->latest()->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('podcasts/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'topic' => ['required', 'string', 'max:255'],
            'tone' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['nullable', 'string', 'max:10'],
            'source_file' => ['nullable', 'file', 'mimes:pdf,txt,doc,docx', 'max:20480'], // 20MB
            'source_text' => ['nullable', 'string'],
        ]);

        $data['user_id'] = $request->user()->id;
        $podcast = Podcast::create($data);

        // If they provided source text, create initial script record
        if ($request->filled('source_text')) {
            PodcastScript::create([
                'podcast_id' => $podcast->id,
                'speaker' => 'Host',
                'raw_text' => $request->string('source_text'),
                'final_text' => null,
            ]);
        }

        // Handle file upload
        if ($request->hasFile('source_file')) {
            $file = $request->file('source_file');
            $stored = $file->store('podcast_sources');
            PodcastFile::create([
                'podcast_id' => $podcast->id,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $stored,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
            ]);
            $podcast->update(['file_uploaded' => true]);
        }

        return redirect()->route('podcasts.show', $podcast);
    }

    public function show(Podcast $podcast): Response
    {
        // $this->authorize('view', $podcast);

        $script = PodcastScript::where('podcast_id', $podcast->id)->first();

        return Inertia::render('podcasts/Show', [
            'podcast' => $podcast,
            'script' => $script,
        ]);
    }
}
