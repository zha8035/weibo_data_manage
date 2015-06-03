[y, Fs] = wavread('msg.wav');
ffy = fft(y);
ans = abs(ffy);
ans
