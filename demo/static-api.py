#!/usr/bin/env python

import errno
import os
import requests
import sys

dir = './api'

def get_args():
    try:
        args = {}
        sys.argv
        args['file'] = sys.argv[1]
        args['base_url'] = sys.argv[2].strip('/')
        if len(sys.argv) > 3:
            # Optional args
            args['user'] = sys.argv[3]
            args['pass'] = sys.argv[4]
        return args
    except IndexError as e:
        print('Error: missing command line arguments:')
        print('# {0} <input-file> <base-url>\n'.format(__file__))

def get_paths(args):

    def filter_lines(line):
        if not line:
            return False
        if line[0] == '#':
            return False
        return True

    with open(args['file']) as f:
        raw_lines = f.readlines()
        lines = [x.strip('\n') for x in raw_lines]

    paths = [x for x in lines if filter_lines(x)]

    return paths

def get_pages(args, paths):

    def mkdir_p(path):
        # http://stackoverflow.com/a/600612/657661
        try:
            os.makedirs(path)
        except OSError as exc: # Python >2.5
            if exc.errno == errno.EEXIST and os.path.isdir(path):
                pass
            else: raise

    base = args['base_url']

    for path in paths:
        url = '{0}/{1}'.format(base, path)
        output_file = '{0}/{1}/index.html'.format(dir, path)
        output_dir = os.path.dirname(output_file)

        if 'user' in args.keys():
            result = requests.get(url, auth=(args['user'], args['pass']))
        else:
            result = requests.get(url)

        mkdir_p(output_dir)
        with open(output_file, 'w+') as f:
            f.write(result.text)

        print(result.status_code, url, output_file)

args = get_args()
paths = get_paths(args)
get_pages(args, paths)
