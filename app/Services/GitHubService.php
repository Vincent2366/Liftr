<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GitHubService
{
    protected $owner;
    protected $repo;
    protected $apiToken;
    
    public function __construct()
    {
        $this->owner = config('services.github.owner', 'Vincent2366');
        $this->repo = config('services.github.repo', 'Liftr');
        $this->apiToken = config('services.github.token');
    }
    
    /**
     * Get all releases from GitHub
     */
    public function getReleases()
    {
        return Cache::remember('github_releases', 3600, function () {
            $response = Http::withHeaders([
                'Accept' => 'application/vnd.github.v3+json',
                'Authorization' => $this->apiToken ? "token {$this->apiToken}" : '',
            ])->get("https://api.github.com/repos/{$this->owner}/{$this->repo}/releases");
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return [];
        });
    }
    
    /**
     * Get the latest release
     */
    public function getLatestRelease()
    {
        return Cache::remember('github_latest_release', 3600, function () {
            $response = Http::withHeaders([
                'Accept' => 'application/vnd.github.v3+json',
                'Authorization' => $this->apiToken ? "token {$this->apiToken}" : '',
            ])->get("https://api.github.com/repos/{$this->owner}/{$this->repo}/releases/latest");
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return null;
        });
    }
    
    /**
     * Format releases for the application
     */
    public function getFormattedReleases()
    {
        $releases = $this->getReleases();
        $formattedReleases = [];
        
        foreach ($releases as $release) {
            $version = ltrim($release['tag_name'], 'v');
            $formattedReleases[$version] = [
                'name' => $release['tag_name'],
                'published_at' => $release['published_at'],
                'is_latest' => !$release['prerelease'],
                'is_prerelease' => $release['prerelease'],
                'body' => $release['body'],
                'html_url' => $release['html_url']
            ];
        }
        
        return $formattedReleases;
    }
}