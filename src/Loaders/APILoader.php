<?php namespace Waavi\Translation\Loaders;

use Illuminate\Translation\LoaderInterface;
use GuzzleHttp\Client;

class APILoader extends Loader implements LoaderInterface
{
    /**
     * The default locale.
     *
     * @var string
     */
    protected $defaultLocale;

    /**
     * api url to retrieve translation data from.
     * @var string
     */
    protected $apiUrl;

    /**
     *  Create a new mixed loader instance.
     *
     *  @param  string                              $defaultLocale
     *  @param  \Illuminate\Translation\FileLoader  $laravelFileLoader
     *  @return void
     */
    public function __construct($defaultLocale, $apiUrl)
    {
        parent::__construct($defaultLocale);
        $this->apiUrl = $apiUrl;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * Load the messages strictly for the given locale without checking the cache or in case of a cache miss.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string  $namespace
     * @return array
     */
    public function loadSource($locale, $group, $namespace = '*')
    {
        $client = new Client();
        $res = $client->request('GET', $this->apiUrl, ['locale'=>$locale]);

        $data = $res->getBody();

        //$data = file_get_contents($this->apiUrl);

        return json_decode($data, true);
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param  string  $namespace
     * @param  string  $hint
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        $this->hints[$namespace] = $hint;
        $this->laravelFileLoader->addNamespace($namespace, $hint);
    }

    /**
     * Get an array of all the registered namespaces.
     *
     * @return array
     */
    public function namespaces()
    {
        return $this->hints;
    }
}
