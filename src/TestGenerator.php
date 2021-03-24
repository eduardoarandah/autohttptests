<?php

namespace EduardoArandaH\AutoHttpTests;

class TestGenerator
{
    public function generate($request, $response)
    {
        if ($this->isUrlBlackListed($request)) {
            return "";
        }
        $actions = [];
        $actions[] = $this->getActingAs($request);
        $actions[] = $this->getMethod($request);
        $actions[] = $this->getStatusCode($response);
        $actions[] = $this->getErrors($request, $response);

        $out = "\n\t\t\$this" . implode("", $actions) . ";";

        return $out;
    }
    public function representArrayKV($array)
    {
        $out = "[";
        foreach ($array as $key => $value) {
            if (!in_array($key, ["_token", "_method"])) {
                if (!is_array($key) && !is_array($value)) {
                    $out .= "\n\t\t\"$key\" => \"$value\",";
                }
            }
        }
        $out .= "\n\t\t]";
        return $out;
    }
    public function representArray($array)
    {
        $out = "[";
        foreach ($array as $key) {
            $out .= "\n\t\t\"$key\",";
        }
        $out .= "\n\t\t]";
        return $out;
    }
    public function shortenUrl($url)
    {
        return str_replace(config("app.url"), "", $url);
    }
    public function isUrlBlackListed($request)
    {
        $shortUrl = $this->shortenUrl($request->getUri());
        $urlBlacklist = ["_debugbar"];

        //urls blacklist
        foreach ($urlBlacklist as $urlIgnored) {
            if (str_contains($shortUrl, $urlIgnored)) {
                return true;
            }
        }
        return false;
    }
    public function getActingAs($request)
    {
        //acting as user
        if ($request->user()) {
            $user = $request->user();
            $class = get_class($user);
            return "\n\t\t->actingAs(\\" .
                $class .
                "::find(" .
                $user->id .
                "))";
        } else {
            return "";
        }
    }
    public function getMethod($request)
    {
        $shortUrl = $this->shortenUrl($request->getUri());
        //make request
        $method = strtolower($request->getMethod());
        if (count($request->all())) {
            $requestParams = $this->representArrayKV($request->all());
            return "\n\t\t->$method(\"$shortUrl\",$requestParams)";
        } else {
            return "\n\t\t->$method(\"$shortUrl\")";
        }
    }
    public function getStatusCode($response)
    {
        //Status
        $status = $response->getStatusCode();
        return "\n\t\t->assertStatus($status)";
    }
    public function getErrors($request, $response)
    {
        //Has errors?
        if ($request->getSession() && $request->getSession()->has("errors")) {
            foreach (
                $request
                    ->getSession()
                    ->get("errors")
                    ->getBags()
                as $errorBag
            ) {
                $fieldsWithError = $this->representArray($errorBag->keys());
                return "\n\t\t->assertSessionHasErrors($fieldsWithError)";
            }
        } else {
            //Redirect
            if ($response->isRedirect()) {
                $redirectUrl = $this->shortenUrl(
                    $response->headers->get("Location")
                );
                return "\n\t\t->assertRedirect(\"$redirectUrl\")";
            }
        }
    }
}
