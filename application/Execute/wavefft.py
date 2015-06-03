# -*- coding: utf-8 -*-
"""
Created on Thu Apr 03 16:57:08 2014

@author: jiran
"""
import sys
import wave
import scipy.signal as signal
import pylab as pl
import numpy as np
import matplotlib
from numpy import diff as diff
filename = sys.argv[1]
f = wave.open(filename,'rb')
params = f.getparams()
nchannels,sampwidth,framerate,nframes = params[:4]
str_data = f.readframes(nframes)
f.close()
wave_data = np.fromstring(str_data,dtype=np.short)
wave_data.shape = -1,nchannels
wave_data = wave_data.T
wave_data = signal.decimate(wave_data,5)
framerate = framerate/5
fhz = float(framerate)
time = np.arange(0,nframes/5+1)
h3 = signal.remez(1000,(0,9.0/fhz,10.0/fhz,200.0/fhz,201.0/fhz,0.5),(0,1,0))
wave_data[0] = signal.lfilter(h3,1,wave_data[0])
wave_data[nchannels-1] = signal.lfilter(h3,1,wave_data[nchannels-1])
wave_data_max = max(abs(wave_data[0]))
wave_data_norm = 10*wave_data[0]/wave_data_max
wave_data_final = wave_data_norm**2-np.average(wave_data_norm**2)
#IndMax=matplotlib.mlab.find(diff(sign(diff(wave_data_final)))<0)+1
maxIndex = matplotlib.mlab.find(wave_data_final == max(wave_data_final))
sound_1 = []
sound_2 = []
#第二心音起始
start = int(maxIndex - 0.6*fhz)
end = int(maxIndex - 0.2*fhz)
wave_data_temp = wave_data_final[start:end]
Max_Index_2 = matplotlib.mlab.find(wave_data_temp == max(wave_data_temp)) + start
pl.figure(1)
pl.subplot(211)
pl.plot(time/fhz,wave_data_final)
pl.plot(maxIndex/fhz,wave_data_final[maxIndex],'Dr')
pl.plot(Max_Index_2/fhz,wave_data_final[Max_Index_2],'*g')
sound_1.append(maxIndex[0])
sound_2.append(Max_Index_2[0])
#第一心音
Max_Index = maxIndex
while (Max_Index -1.2*fhz)>0:
    start = int(Max_Index - 1.2*fhz)
    end = int(Max_Index - 0.6*fhz)
    wave_data_temp = wave_data_final[start:end]
    Max_Index = matplotlib.mlab.find(wave_data_temp == max(wave_data_temp)) + start
    pl.plot(Max_Index/fhz,wave_data_final[Max_Index],'Dr')
    sound_1.append(Max_Index[0])
Max_Index = maxIndex
while (Max_Index +1.2*fhz)<(nframes/5+1):
    start = int(Max_Index + 0.6*fhz)
    end = int(Max_Index + 1.2*fhz)
    wave_data_temp = wave_data_final[start:end]
    Max_Index = matplotlib.mlab.find(wave_data_temp == max(wave_data_temp)) + start
    pl.plot(Max_Index/fhz,wave_data_final[Max_Index],'Dr')
    sound_1.append(Max_Index[0])
#第二心音
Max_Index = Max_Index_2
while (Max_Index -1.2*fhz)>0:
    start = int(Max_Index - 1.2*fhz)
    end = int(Max_Index - 0.6*fhz)
    wave_data_temp = wave_data_final[start:end]
    Max_Index = matplotlib.mlab.find(wave_data_temp == max(wave_data_temp)) + start
    pl.plot(Max_Index/fhz,wave_data_final[Max_Index],'*g')
    sound_2.append(Max_Index[0])
Max_Index = Max_Index_2
while (Max_Index +1.2*fhz)<(nframes/5+1):
    start = int(Max_Index + 0.6*fhz)
    end = int(Max_Index + 1.2*fhz)
    wave_data_temp = wave_data_final[start:end]
    Max_Index = matplotlib.mlab.find(wave_data_temp == max(wave_data_temp)) + start
    pl.plot(Max_Index/fhz,wave_data_final[Max_Index],'*g')
    sound_2.append(Max_Index[0])
    
    
    
#pl.figure(1)
#pl.subplot(211)
#pl.plot(time/fhz,wave_data_final)
#pl.plot(IndMax/fhz,wave_data_final[IndMax],'D')
pl.subplot(212)
pl.plot(time/fhz,wave_data[nchannels-1],c='g')
pl.xlabel("time(seconds)")
pl.savefig('peaks.png')
fft_size = 16384
wave_data_1 = wave_data[0][:fft_size]
wave_fft = np.fft.fft(wave_data_1)/fft_size
freqs = np.linspace(0,fhz/2,fft_size/2+1)
xfp = 20*np.log10(np.clip(np.abs(wave_fft),1e-20,1e100))
pl.figure(2)
pl.plot(freqs,xfp[:fft_size/2+1])
pl.savefig('fft.png')
pl.figure(3)
pl.subplot(221)
s = diff(np.sort(sound_1))/fhz
pl.hist(s)
pl.xlabel('Sound_1 Period(sec)')
pl.subplot(222)
s = diff(np.sort(sound_2))/fhz
pl.hist(s)
pl.xlabel('Sound_2 Period(sec)')
pl.subplot(212)
sound_1.extend(sound_2)
s = diff(np.sort(sound_1))/fhz
pl.hist(s)
pl.xlabel('S1-S2 time(sec)')
pl.savefig('times.png')
