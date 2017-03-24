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
    protected $apiUri;

    /**
     *  Create a new mixed loader instance.
     *
     *  @param  string                              $defaultLocale
     *  @param  \Illuminate\Translation\FileLoader  $laravelFileLoader
     *  @return void
     */
    public function __construct($defaultLocale, $apiUri)
    {
        parent::__construct($defaultLocale);
        $this->apiUri = $apiUri;
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
        $client = new Client([
          'base_uri' => $this->apiUri
        ]);
        $res = $client->request('GET',
          '/translations',
          ['query' => [
            'locale'=>$locale,
            'group'=>$group
          ]]
        );

        $rsContent = $res->getBody()->getContents();
        $rawData = json_decode($rsContent, true);
        $data = [];

        foreach ($rawData as $key => $translation) {
          if ($group && substr($key, 0, strlen($group))==$group) {
            $key = substr($key, strlen($group)+1);
          }
          $data[$key] = $translation;
        }

        return $data;
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
        //$this->laravelFileLoader->addNamespace($namespace, $hint);
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
