Docker for Mac Filesystem Performance Test
------------------------------------------

Tests the filesystem performance of a volume at `/mounted`.  Runs a simple PHP script to test stat, open, and write calls.

Usage:
```
> docker build .
...
Successfully built 54952549b940

# Don't forget to actually mount something at /mounted.
> docker run -v /some/volume:/mounted 54952549b940
Finished stat x 1000.  Local: 2ms, Mounted: 245ms (11156% difference)
Finished open x 1000.  Local: 3ms, Mounted: 778ms (19496% difference)
Finished write x 1000.  Local: 1ms, Mounted: 249ms (14827% difference)
```