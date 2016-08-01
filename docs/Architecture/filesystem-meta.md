# Filesystem Abstraction Layer

## Why bother?

### What exactly is the problem?

Web applications use a lot of media, that are stored in a file system locally or on remote servers.

There are many different kinds of file systems. Each one has different structure and logic, properties of speed, flexibility, security, size and more.
Some file systems are used on local data storage devices, others provide file access via a network protocol.
Some file systems are "virtual", in that the "files" supplied are computed on request or are merely a mapping into a different file system used as a backing store.

The file system manages access to both the content of files and the metadata about those files.

### Why is it relevant to architecture?

The architecture should provide an API to access files on different file systems transparently. 

### What are the consequences of the decision?

An abstraction layer on filesystem level provides the possibility to hide different storage engines behind a common API.

## Factors

### What conditions must be fulfilled?

#### Union Mount

![Layered file system](../assets/filesystem-layered.png)

Some applications require the ability to override files, which should be possible without losing the ability for updates.
Path mapping can be implemented to cater for overrides.
A virtual layer can be used to implement transactions for filesystem changes - think of automatic updates. 

### What quality objectives have to be considered?
### Which stakeholders are involved?

## Criteria

### How do we choose alternatives?
### By what criteria do we evaluate alternatives?
### How do we weight the criteria with one another?

## Options

### Which solutions do we consider?
### Which solutions do we not consider?
### How do we evaluate each option in terms of the criteria?
### What assumptions did we make?
### What risks do we have to be aware of?

## Solution

### What is the decision?
### Who has taken it or participated in it?
### What is the reasoning?
### When was it decided?



## Why bother?



## Goals

  - Generic API for handling common tasks across multiple file storage engines.
  - Support streams for big file handling.
  - Offer a possibility to mount different filesystems distributed across a network.
  - Emulate directories in systems that support none, like AwsS3.
  - Be cachable.
  - Allow adapting to other/new services.
  - Make it easy to test filesystem interactions.

## Options

The currently available options seem to be:

  - [Native PHP](http://php.net)
  - [Filicious](http://filicious.org)
  - [Gaufrette](https://github.com/KnpLabs/Gaufrette)
  - [SF2 Filesystem](http://symfony.com/doc/current/components/filesystem.html)
  - [Flysystem](http://flysystem.thephpleague.com/)
  
## Comparision

<table>
    <thead>
        <tr>
            <th></th>
            <th>Native PHP</th>
            <th>Filicious</th>
            <th>Gaufrette¹</th>
            <th>SF2 Filesystem</th>
            <th>Flysystem</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <th>Abstraction level</th>
            <td>-</td><td>+</td><td>o</td><td>o</td><td>+</td>
        </tr>

        <tr>
            <th>Stream wrapper support</th>
            <td>+</td><td>+</td><td>o</td><td>-</td><td>+</td>
        </tr>

        <tr>
            <th>Union mount</th>
            <td>-</td><td>+</td><td>-</td><td>-</td><td>+</td>
        </tr>

        <tr>
            <th colspan="6" class="text-center">Supported filesystems</th>
        </tr>
        <tr>
            <th>Local</th>
            <td>+</td><td>+</td><td>o</td><td>o</td><td>+</td>
        </tr>
        <tr>
            <th>Amazon S3</th>
            <td>-</td><td>-</td><td>o</td><td>-</td><td>+</td>
        </tr>
        <tr>
            <th>Azure</th>
            <td>-</td><td>-</td><td>o</td><td>-</td><td>+</td>
        </tr>
        <tr>
            <th>Copy.com</th>
            <td>-</td><td>-</td><td>-</td><td>-</td><td>+</td>
        </tr>
        <tr>
            <th>Dropbox</th>
            <td>-</td><td>-</td><td>o</td><td>-</td><td>+</td>
        </tr>
        <tr>
            <th>FTP</th>
            <td>o</td><td>o</td><td>o</td><td>-</td><td>+</td>
        </tr>
        <tr>
            <th>GridFS</th>
            <td>-</td><td>-</td><td>o</td><td>-</td><td>+</td>
        </tr>
        <tr>
            <th>Memory</th>
            <td>-</td><td>-</td><td>o</td><td>-</td><td>+</td>
        </tr>
        <tr>
            <th>Null / Test</th>
            <td>-</td><td>-</td><td>-</td><td>-</td><td>+</td>
        </tr>
        <tr>
            <th>Rackspace</th>
            <td>-</td><td>-</td><td>o</td><td>-</td><td>+</td>
        </tr>
        <tr>
            <th>SFTP</th>
            <td>o</td><td>+</td><td>o</td><td>-</td><td>+</td>
        </tr>
        <tr>
            <th>WebDAV</th>
            <td>-</td><td>-</td><td>-</td><td>-</td><td>+</td>
        </tr>
        <tr>
            <th>ZipArchive</th>
            <td>-</td><td>-</td><td>o</td><td>-</td><td>+</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6">
                + full support &nbsp; o partial support &nbsp; - no support<br />
                1 Gaufrette has no support for directory operations
            </td>
        </tr>
    </tfoot>
</table>

### Mounting

Filicious and Flysystem use different concepts for access across filesystems.

#### Filicious

Filicious utilises a merged filesystem similar to the UNIX union mount.
With the merged filesystem several other filesystems can be mounted into a virtual structure.

```php
$filesystem = new MergedFilesystem();

$filesystem->mount('/home', new LocalFilesystem('/path/to/directory'));
$filesystem->mount('/remote/server', new FTPFilesystem($config));

$source = $filesystem->getFile('/home/');
$target = $filesystem->getFile('/remote/server/');

$source->copyTo($target);
```

#### Flysystem

Flysystem comes with a wrapper class to easily work with multiple file system instances from a single object.
The MountManager is an easy to use container simplifying more complex cross file system interactions.

```php
$ftp = new League\Flysystem\Filesystem($ftpAdapter);
$local = new League\Flysystem\Filesystem($localAdapter);

$filesystem = new League\Flysystem\MountManager([
    'local' => $local,
    'backup' => $ftp,
]);

$filesystem->copy('local://some/file.ext', 'backup://storage/location.ext');
```

## Solution

**Flysystem will be used, as it best supports the defined goals.
A path mapper will be added to allow virtual path reconfiguration.**

The path mapper will be implemented as a decorator for League\Flysystem\Filesystem.

## Tasks

  - The Joomla! filesystem related classes must be refactored to use Flysystem under the hood.
  - The path mapper must be implemented as a decorator for League\Flysystem\Filesystem.

## Consequences

**3PD** MUST use the Joomla! filesystem classes to access files and directories.
Otherwise, it cannot be guaranteed, that all paths will work after an update.
