#!/usr/bin/python

import wave
import scipy
import numpy
from scipy.io import wavfile
import json

rate, data = wavfile.read('msg.wav')
ans = numpy.abs(numpy.fft.fft(data))
print json.dumps(ans.tolist())
