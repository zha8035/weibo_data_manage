import sys
import math

begin = int(sys.argv[1])
end = int(sys.argv[2])
for i in xrange(begin, end):
	print math.sin(i/math.pi)*100
