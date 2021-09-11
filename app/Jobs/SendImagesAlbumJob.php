<?php

namespace App\Jobs;

use App\Events\PhotosChanges;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Events\ScreenChanges;
use Illuminate\Support\Facades\Auth;

class SendImagesAlbumJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $dados;
    protected $path;
    protected $repository;
    protected $uploadPlugin;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dados, $path, $repository, $uploadPlugin)
    {
        $this->dados = $dados;
        $this->path = $path;
        $this->repository = $repository;
        $this->uploadPlugin = $uploadPlugin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $fotos = $this->dados->file('photos');
            $albumId = Arr::get($this->dados, "album_id");

            foreach ($fotos as $foto) {
                $newPhoto = [];
                $pathPhoto = $this->uploadPlugin->upload($foto, $this->path);

                if (!$pathPhoto) {
                    continue;
                }

                $photoId = $this->repository->updateOrCreate(['path' => $pathPhoto]);
                $newPhoto["photo_id"] = $photoId->id;
                $newPhoto["album_id"] = $albumId;
                array_push($arrFotos, $newPhoto);
            }

            $album = $this->repository->find($albumId);

            $album->photos()->attach(
                $arrFotos
            );

            broadcast(new PhotosChanges(Auth::user()->company_id))->toOthers();
            broadcast(new ScreenChanges(Auth::user()->company_id))->toOthers();

            return redirect()->back()->with('message', 'Registro criado/atualizado!');
        } catch (\Exception $ex) {
            Log::error(__METHOD__ . ' ' . $ex);
        }
    }

    public function tags()
    {
        return ['send_images_album'];
    }
}
