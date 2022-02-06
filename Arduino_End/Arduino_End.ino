#include <Servo.h>

Servo myservo;
#define NUM_SAMPLES 2000 //Num of samples (1 Hz)

int sum = 0;
unsigned char sample_count = 0;
float voltage = 0.0;

float approx_best_v = 0.0;
bool goUp = true;
int servo_pos = 90;

void setup()
{
    Serial.begin(9600);
    myservo.attach(9);//connect pin 9 with the control line(the middle line of Servo) 
    myservo.write(servo_pos);// move servos to center position -> 90°
}

void printFloat(float value, int places) {
  // this is used to cast digits
  int digit;
  float tens = 0.1;
  int tenscount = 0;
  int i;
  float tempfloat = value;

    // make sure we round properly. this could use pow from <math.h>, but doesn't seem worth the import
  // if this rounding step isn't here, the value  54.321 prints as 54.3209

  // calculate rounding term d:   0.5/pow(10,places)  
  float d = 0.5;
  if (value < 0)
    d *= -1.0;
  // divide by ten for each decimal place
  for (i = 0; i < places; i++)
    d/= 10.0;    
  // this small addition, combined with truncation will round our values properly
  tempfloat +=  d;

  // first get value tens to be the large power of ten less than value
  // tenscount isn't necessary but it would be useful if you wanted to know after this how many chars the number will take

  if (value < 0)
    tempfloat *= -1.0;
  while ((tens * 10.0) <= tempfloat) {
    tens *= 10.0;
    tenscount += 1;
  }


  // write out the negative if needed
  if (value < 0)
    Serial.print('-');

  if (tenscount == 0)
    Serial.print(0, DEC);

  for (i=0; i< tenscount; i++) {
    digit = (int) (tempfloat/tens);
    Serial.print(digit, DEC);
    tempfloat = tempfloat - ((float)digit * tens);
    tens /= 10.0;
  }

  // if no places after decimal, stop now and return
  if (places <= 0)
    return;

  // otherwise, write the point and continue on
  Serial.print('.');  

  // now write out each decimal place by shifting digits one by one into the ones place and writing the truncated value
  for (i = 0; i < places; i++) {
    tempfloat *= 10.0;
    digit = (int) tempfloat;
    Serial.print(digit,DEC);  
    // once written, subtract off that digit
    tempfloat = tempfloat - (float) digit;
  }
}

float getVoltage(){

    sample_count = 0;
    sum = 0;
  
    while (sample_count < 10) {
        sum += analogRead(A2);
        sample_count++;
        delay(10);
    }

    float retval = ((float)sum / (float)10 * 5.015) / 1024.0;

    sample_count = 0;
    sum = 0;

    return retval * 11.123;
}

void loop()
{

    voltage = getVoltage();

    printFloat(voltage, 2);
    Serial.println("");

    //Initiate manual search
    if(voltage > (approx_best_v + .04) || voltage < (approx_best_v-.04)){
      
        approx_best_v = voltage;
        int iters = 0;
        
        while(!((servo_pos + 5) > 135)){
            servo_pos = servo_pos + 5;
            myservo.write(servo_pos);
            delay(500);
            voltage = getVoltage();
            if(voltage > (approx_best_v + .05)) {
                approx_best_v = voltage;
                iters++;
            }else{
                servo_pos -= 5;
                myservo.write(servo_pos);
                break;
            }
        }
        
        if(iters == 0) {
            while(!((servo_pos - 5) < 45)){
                servo_pos = servo_pos - 5;
                myservo.write(servo_pos);
                delay(500);
                voltage = getVoltage();
                if(voltage > (approx_best_v + .05)) {
                    approx_best_v = voltage;
                }else{
                    servo_pos += 5;
                    myservo.write(servo_pos);
                    break;
                }
            }
        }

      approx_best_v = voltage;
    }
    
    sample_count = 0;
    sum = 0;
}
